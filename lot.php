<?php
session_start();
require_once "functions.php";

$category = get_category($link);
$cookie_history = "history";
$expire_date = time()+60*60*24*30;
$history_path = "/";

// Нужно поставить условие, что если в header есть get["newLot"], то нужно запросить из БД последний добавленный лот.
// Или, возможно лучше будет добавить запрос о последнем добавленном лоте в add.php, и просто прислать сюда нужный индекс.

if (isset($_GET["id"])) {
    $lot = get_lot_byId($link, $_GET["id"]);
    $bets = get_bets_byLotId($link, $_GET["id"]);
    if ($lot == "Нет такого лота") {
        header("Location: ../error404.php");
    };
};

if (!isset($_COOKIE[$cookie_history]) and $_GET["id"]) {
    $new_data = [$_GET["id"]];
    $cookie_data = json_encode($new_data);
    setcookie($cookie_history, $cookie_data, $expire_date, $history_path);
} elseif (isset($_COOKIE[$cookie_history]) and $_GET["id"]) {
    $old_data = json_decode($_COOKIE["history"], true);
    if (!in_array($_GET["id"], $old_data)) {
        $old_data[] = $_GET["id"];
        $new_data = json_encode($old_data);
        unset($_COOKIE[$cookie_history]);
        setcookie($cookie_history, $new_data, $expire_date, $history_path);
    };
};

$link->close();

$content_page = render_template("templates/lot.php", ["lot" => $lot, "bets" => $bets]);

renderPage("templates/layout.php", ["content" => $content_page, "title" => $lot["title"],
    "category" => $category
]);
?>
