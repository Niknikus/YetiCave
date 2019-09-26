<?php
require_once 'functions.php';
$page_content = render_template('templates/index.php',  [[
    'title' => '2014 Rossignol District Snowboard',
    'category' => 'Доски и лыжи',
    'price' => 10999,
    'src' => 'img/lot-1.jpg'],
    [
        'title' => 'DC Ply men 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 159999,
        'src' => 'img/lot-2.jpg'
    ],
    [
        'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'price' => 8000,
        'src' => 'img/lot-3.jpg'
    ],
    [
        'title' => 'Ботинки для сноуборда DC Mutiny Charcoal',
        'category' => 'Ботинки',
        'price' => 10999,
        'src' => 'img/lot-4.jpg'
    ],
    [
        'title' => 'Куртка для сноуборда DC Mutiny Charcoal',
        'category' => 'Одежда',
        'price' => 7500,
        'src' => 'img/lot-5.jpg'
    ],
    [
        'title' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'price' => 5400,
        'src' => 'img/lot-6.jpg'
    ]
]);
renderPage('templates/layout.php', ['content' => $page_content, 'title' => 'Магазин для сноубордистов',
    'user_name' => 'Николай',
    'user_avatar' => 'img/user.jpg',
    'is_auth' => 'true',
    'category' => ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное']]);

?>

