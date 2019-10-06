<?php

require_once 'functions.php';
require_once 'lot-list.php';
if (!isset($_GET['newlot'])) {
    $index = $_GET['id'] - 1;
    if (!($lots_list[$index])) {
        header('Location: ../error404.php');
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
