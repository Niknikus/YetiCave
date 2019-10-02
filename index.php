<?php
require_once 'functions.php';
require_once 'lot-list.php';
$lots_list = add_id_file(1, 'lot', '.php', $lots_list);
$page_content = render_template('templates/index.php',  ['lots_list' => $lots_list]);
renderPage('templates/layout.php', ['content' => $page_content, 'title' => 'Магазин для сноубордистов',
    'user_name' => 'Николай',
    'user_avatar' => 'img/user.jpg',
    'category' => ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'],
    'is_auth' => (boolean) rand(0, 1)]);

?>

