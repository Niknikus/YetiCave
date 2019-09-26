<?php
require_once 'functions.php';
require_once 'lotFunc.php';
$lots_list = add_id_file(1, 'templates/lot', '.php', $lots_list);
$page_content = render_template('templates/index.php',  $lots_list);
renderPage('templates/layout.php', ['content' => $page_content, 'title' => 'Магазин для сноубордистов',
    'user_name' => 'Николай',
    'user_avatar' => 'img/user.jpg',
    'is_auth' => 'true',
    'category' => ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное']]);

?>

