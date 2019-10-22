<?php

session_start();
require_once 'functions.php';
$category = get_category($link);
$lots_list = get_lots($link);
$page_content = render_template('templates/index.php',  ['lots_list' => $lots_list]);
renderPage('templates/layout.php', ['content' => $page_content, 'title' => 'Магазин для сноубордистов',
    'category' => $category]);
