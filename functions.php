<?php
// Подключаю ресурс соединения
require_once "init.php";

// Шаблонизатор контента
function render_template($rout_to_template, $data) {
    ob_start();
    extract($data);
    require_once $rout_to_template;
    return ob_get_clean();
}

// Шаблонизатор страниц
function renderPage($rout_to_layout, $data) {
    ob_start();
    extract($data);
    require_once $rout_to_layout;
    $layout = ob_get_clean();
    print($layout);
}

// Отображение формата цены
function price_format($integer) {
    $new_price = ceil($integer);
    if ($integer < 1000) {
        $new_price = (string) $new_price;
        return $new_price . ' ' . '&#8381;';
    }
    else {
        $new_price = number_format($new_price, 0, ',', ' ');
        $new_price = (string) $new_price;
        return $new_price . ' ' . '&#8381;';
    }
}

// Счетчик времени до полуночи.
function time_to_midnight() {
    $time = $_SERVER["REQUEST_TIME"];
    $midnight_time = strtotime('tomorrow midnight');
    $expire_time = $midnight_time - $time;
    $hour = floor($expire_time/3600);
    $minute = floor(($expire_time - ($hour * 3600)) / 60);
    $sec = $expire_time - $hour * 3600 - $minute * 60;
    $result = sprintf("%02d:%02d:%02d", $hour, $minute, $sec);
    print($result);
}

/**
 * @param integer $addBetTime Принимает timestamp когда была сделана ставка
 * @return string Возвращает нужную дату, когда была сделан ставка по отношению к текущему моменту.
 */
function time_from_bet($addBetTime) {
    $now = $_SERVER["REQUEST_TIME"];
    $diff = $now - $addBetTime;
    if ($diff < 300) {
        return "Только что";
    }
    elseif ($diff >= 300 and $diff < 1200) {
        return "5 минут назад";
    }
    elseif ($diff >= 1200 and $diff < 3600) {
        return "20 минут назад";
    }
    elseif ($diff >= 3600 and $diff < 7200) {
        return "Час назад";
    }
    else {
        $result = date("d.m.y", $addBetTime) . " в " . date("h:i", $addBetTime);
        return $result;
    }
}

/**
 * Счётчик времени до окончания торгов
 * @param $expire_date integer принимает дату окончания торгов
 * @return string возвращает строку для timer span в лоте
 */
function expire_lotTime($expire_date) {
    $now = $_SERVER["REQUEST_TIME"];
    $diff = $expire_date - $now;
    if (floor($diff/86400) >= 1) {
        $result = floor($diff/86400) . " дней ";
        return $result;
    } else {
        $hour = floor($diff/3600);
        $minute = floor(($diff - ($hour * 3600)) / 60);
        $sec = $diff - $hour * 3600 - $minute * 60;
        $result = sprintf("%02d:%02d:%02d", $hour, $minute, $sec);
        return $result;
    }
}


// Функция для работы с id из cookies
function add_id_param($item) {
    $new_item = "lot.php?id=" . $item;
    return $new_item;
}

// Коррекция текста из данных от пользователей
function beauty_characters($text) {
    $text = trim($text);
    $text = stripcslashes($text);
    $text = strip_tags($text);
    $text = htmlspecialchars($text);
    return $text;
}

function query_error($error, $template = "templates/error-query.php", $layout = "templates/layout.php") {
    $header = "Ошибка подключения к БД";
    $content_page = render_template($template, ["header" => $header, "errors" => $error]);
    renderPage($layout, ["content" => $content_page, "title" => "Ошибка подключения к базе данных"]);
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
}

/**
 * Запрос на получение категорий.
 * @param $link mysqli русурс соединения
 * @return $categories_list массив категорий.
*/
function get_category($link) {
    $categories_list = [];
    $sql_categories = "select `name` from `categories`";
    $result = mysqli_query($link, $sql_categories);
    if (!$result->num_rows) {
        query_error("Отсутствуют категории товаров в БД");
    } else {
        while ($row=mysqli_fetch_array($result)) {
                $categories_list[] = $row['name'];
        };
    };
    return $categories_list;
}

/**
 * Получение юзера из БД по email
 * @param $link mysqli ресурс соединения
 * @param $email string пользовательская почта
 * @return object с результатом
*/
function get_user_by_email($link, $email) {
    $sql = "select * from users where email=?";
    $stmt = db_get_prepare_stmt($link, $sql, [$email]);
    $stmt->execute();
    return $stmt->get_result();
}

function add_user($link, $reg_date, $name, $email, $img, $password, $contacts) {
    $sql = "INSERT INTO users (reg_date, name, email, img, password, contacts) VALUES (?,?,?,?,?,?)";
    $stmt = db_get_prepare_stmt($link, $sql, [$reg_date, $name, $email, $img, $password, $contacts]);
    $stmt->execute();
    $id = 0;
    if ($stmt->affected_rows > 0) {
        return $id = mysqli_insert_id($link);
    } else {
        return false;
    }
}

function add_lot($link, $title, $description, $img, $add_date, $expire_date, $category_id, $user_id, $curr_price, $step) {
    $start_price = $curr_price;
    $sql = "INSERT INTO lots (title, description, img, add_date, expire_date, category_id, user_id, curr_price, step, start_price) VALUES 
(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = db_get_prepare_stmt($link, $sql, [$title, $description, $img, $add_date, $expire_date, $category_id, $user_id, $curr_price, $step, $start_price]);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $id = mysqli_insert_id($link);
        return $id;
    } else {
        return false;
    }
}

function user_savings($array_post) {
    $savings = [];
    foreach ($array_post as $key => $value) {
        if ($key == "password") {
            continue;
        }
        elseif (is_string($value)) {
            $savings[$key] = beauty_characters($value);
        } else {
            $savings[$key] = $value;
        };
    };
    return $savings;
}

// Получение лотов из таблицы

function get_lots($link) {
    $sql = "SELECT l.`id`, `title`, `description`, `img`, c.`name`, `curr_price` FROM lots l JOIN categories c ON l.`category_id` = c.`id` ORDER BY `add_date` DESC";
    $result = mysqli_query($link, $sql);
    $lots = [];
    while ($row=mysqli_fetch_array($result)) {
        $lots[] = $row;
    }
    return $lots;
}

function get_lot_byId($link, $id) {
    $sql = "SELECT l.`id`, `title`, `description`, `img`, c.`name`, `expire_date`, `curr_price`, `step` FROM lots l JOIN categories c ON l.category_id = c.id WHERE l.id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$id]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows) {
        $lot = $result->fetch_assoc();
        return $lot;
    } else {
        $error = "Нет такого лота.";
        return $error;
    }
}

function get_bets_byLotId($link, $lotID) {
    $sql = "SELECT `add_date`, `price`, u.`name` FROM bets b JOIN users u ON b.user_id = u.id WHERE b.lot_id = ? ORDER BY `add_date` DESC";
    $stmt = db_get_prepare_stmt($link, $sql, [$lotID]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows) {
        $bets = [];
        for ($i = 1; $i <= $result->num_rows; $i++) {
            $bets[] = $result->fetch_assoc();
        };
        return $bets;
    }
}

/**
 * Class Bet для работы со ставками
 */
class Bet {
    /**
     * check_bet проверяет, корректная ли ставка
     * @param $link mysqli ресурс соединения
     * @param $lotID integer id лота на котором делают ставку
     * @param $bet integer ставка
     * @return boolean
     */
    public static function check_bet ($link, $lotID, $bet) {
        $sql = "SELECT `curr_price`, `step` FROM lots WHERE `id`= $lotID";
        $result = mysqli_query($link, $sql);
        $table = [];
        while ($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                $table[$key] = $value;
            }
        };
        if (count($table)) {
            if ($bet >= ($table["curr_price"] + $table["step"])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * add_bet добавляет ставку в базу
     * @param $link mysqli русурс соединения
     * @param $date integer timestamp добавления ставки
     * @param $bet integer ставка
     * @param $lot_id integer id лота
     * @param $user_id integer id пользователя который делает ставку
     */
    public static function add_bet ($link, $date, $bet, $lot_id, $user_id) {
        mysqli_query($link, "START TRANSACTION");
        $stmt_1 = mysqli_query($link, "INSERT INTO `bets` (`add_date`, `price`, `lot_id`, `user_id`) VALUES ($date, $bet, $lot_id, $user_id)");
        $stmt_2 = mysqli_query($link, "UPDATE `lots` SET `winner_id` = $user_id, `curr_price` = $bet WHERE `id` = $lot_id");
        if ($stmt_1 && $stmt_2) {
            mysqli_query($link, "COMMIT");
        } else {
            mysqli_query($link, "ROLLBACK");
            query_error("Ошибка подключения к БД");
        };
        header("Location: lot.php?id=" . $lot_id);
}
}
/**
 * Объект для работы с паролями
 */
class Password {
    /**
     * @param $password string принимает пользовательский ввод
     * @return string возвращает хэш
     */
    public static function hash($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @param $password string принимает пользовательский ввод
     * @param $hash string принимает хэш из БД
     * @return bool возвращает true|false
     */
    public static function verify($password, $hash) {
        return password_verify($password, $hash);
    }
}
