<?php
session_start();
require_once 'functions.php';
require_once 'lot-list.php';

$cookie_history = 'history';
$expire_date = time()+60*60*24*30;
$history_path = '/';

if (!isset($_GET['newlot'])) {
    $index = $_GET['id'] - 1;
    if (!($lots_list[$index])) {
        header('Location: ../error404.php');
    };
};

if (!isset($_COOKIE[$cookie_history]) and $_GET['id']) {
    $new_data = [$index];
    $cookie_data = json_encode($new_data);
    setcookie($cookie_history, $cookie_data, $expire_date, $history_path);
} elseif (isset($_COOKIE[$cookie_history]) and $_GET['id']) {
    $old_data = json_decode($_COOKIE['history'], true);
    if (!in_array($index, $old_data)) {
        $old_data[] = $index;
        $new_data = json_encode($old_data);
        unset($_COOKIE[$cookie_history]);
        setcookie($cookie_history, $new_data, $expire_date, $history_path);
    };
};



$content_page = render_template('templates/lot.php', ['lot' => $lots_list[$index]]);

renderPage('templates/layout.php', ['content' => $content_page, 'title' => $lots_list[$index]['title'],
    'user_name' => 'Гость',
    'user_avatar' => 'img/user.jpg',
    'category' => ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'],
    'is_auth' => (boolean) rand(0, 1)
]);
?>
