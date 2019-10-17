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
    date_default_timezone_set('Europe/Moscow');
    $curr_date = strtotime('now');
    $hour_c = date('H', $curr_date);
    $min_c = date('i', $curr_date);
    $zero_H = 23;
    $zero_M = 60;
    $result = ($zero_H - $hour_c) . ':' . ($zero_M - $min_c);
    print($result);
}

// Функция для работы с id из cookies
function add_id_file($startIndex, $fileName, $postFix, $array) {
    $index = $startIndex;
    $new_array = $array;
    foreach ($array as $item => $value) {
        $new_array[$index-1]['root'] = $fileName . $postFix . '?' . 'id=' . $index ;
        $index += 1;
    }
    return $new_array;
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
    $content_page = render_template($template, ["data" => ["header" => $header, "error" => $error]]);
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

// Запрос на получение категорий.
function get_category($link) {
    $categories_list = [];
    $sql_categories = "select `name` from `categories`";
    $result = mysqli_query($link, $sql_categories);
    if (!$result) {
        query_error("Отсутствуют категории товаров в БД");
    } else {
        while ($row=mysqli_fetch_array($result)) {
                $categories_list[] = $row['name'];
        };
    };
    return $categories_list;
}

// Получение юзера из БД по email
function get_user_by_email($link, $email) {
    $sql = "select * from users where email=?";
    $stmt = db_get_prepare_stmt($link, $sql, [$email]);
    $stmt->execute();
    return $stmt->get_result();
}

function add_user($link, $reg_date, $name, $email, $img, $password, $contacts) {
    $sql = "insert into users (reg_date, name, email, img, password, contacts) VALUES (?,?,?,?,?,?)";
    $stmt = db_get_prepare_stmt($link, $sql, [$reg_date, $name, $email, $img, $password, $contacts]);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        return true;
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

class Password {
    public static function hash($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    public static function verify($password, $hash) {
        return password_verify($password, $hash);
    }
}
