<?php

session_start();
require_once 'functions.php';
require_once 'lot-list.php';
$category = get_category($link);
$lots_list = add_id_file(1, 'lot', '.php', $lots_list);
$page_content = render_template('templates/index.php',  ['lots_list' => $lots_list]);
renderPage('templates/layout.php', ['content' => $page_content, 'title' => 'Магазин для сноубордистов',
    'category' => $category]);
