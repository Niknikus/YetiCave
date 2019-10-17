<?php

$link = mysqli_connect('localhost', 'root', '', 'yeticave');

//Обработаем ошибку соединения

if (!$link) {
    $content_page = render_template("templates/error-query.php", ["header" => "Произошла ошибка:", "errors" => [mysqli_connect_error()]]);
    renderPage("templates/layout.php", ["content" => $content_page,
        "title" => "Ошибка подключения к БД",
        "category" => ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"]
    ]);
};

mysqli_set_charset($link,"utf8");
