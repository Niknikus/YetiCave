<?php
require_once 'functions.php';
$category = get_category($link);
session_start();
if (isset($_COOKIE['history'])) {
    $id_arr = json_decode($_COOKIE['history'], true);
    $lots = [];
    foreach ($id_arr as $item => $value) {
        $lots[] = get_lot_byId($link, $value);
    };
    $content_page = render_template('templates/history.php', ['lots' => $lots]);
    renderPage('templates/layout.php', ['content' => $content_page,
        'title' => 'История просмотров',
        'category' => $category]);
} else {
    renderPage('templates/layout.php', ['title' => 'Нет истории просмотров',
        'category' => $category]);
};

?>
