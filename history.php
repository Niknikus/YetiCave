<?php
require_once 'functions.php';
require_once 'lot-list.php';

if (isset($_COOKIE['history'])) {
    $id_arr = json_decode($_COOKIE['history'], true);
    $lots = [];
    foreach ($id_arr as $item => $value) {
        $lots[] = $lots_list[$value];
    };
    $content_page = render_template('templates/history.php', ['lots' => $lots]);
    renderPage('templates/layout.php', ['content' => $content_page,
        'title' => 'История просмотров',
        'user_name' => 'Николай',
        'user_avatar' => 'img/user.jpg',
        'category' => ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'],
        'is_auth' => (boolean) rand(0, 1)]);
} else {
    renderPage('templates/layout.php', ['title' => 'Нет истории просмотров',
        'user_name' => 'Николай',
        'user_avatar' => 'img/user.jpg',
        'category' => ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'],
        'is_auth' => (boolean) rand(0, 1)]);
};

?>
