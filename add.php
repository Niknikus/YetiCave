<?php
require_once 'functions.php';
session_start();
// Запрет для неавторизованных пользователей.
if (!isset($_SESSION['is_auth'])) {
   http_response_code(403);
   exit();
};

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $required = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
    $dict = ['lot-name' => 'Вы не написали название лота.',
        'category' => 'Вы не выбрали категорию товара.',
        'message' => 'Вы не заполнили поле с описанием товара.',
        'lot-rate' => 'Вы не указали начальную цену лота',
        'lot-step' => 'Вы не указали шаг торгов.',
        'lot-date' => 'Вы не заполнили поле дата.'];
    $error_list = [];
    $error_savings = [];
    $valid_data = [];
    $lotSchema = [
        'title' => '',
        'category' => '',
        'price' => 0,
        'src' => '',
        'description' => ''
    ];
    foreach ($required as $key => $value) {
        if (!empty($_POST[$key])) {
            $error_list[$key] = $dict[$key];
        }
    };
    if ($_POST['category'] == 'Выберите категорию') {
        $error_list['category'] = $dict['category'];
    };

    if ($_POST['lot-rate']) {
      $temp = $_POST['lot-rate'];
      if (!is_numeric($temp) or $temp <=0) {
          $error_list['lot-rate'] = 'Указали невозможную цену лота';
      } else {
          $valid_data['lot-rate'] = $_POST['lot-rate'];
      };
    } else {
        $error_list['lot-rate'] = 'Вы не указали цену лота';
    };

    if ($_POST['lot-step']) {
      if (!is_numeric($_POST['lot-step']) or $_POST['lot-step'] <= 0) {
          $error_list['lot-step'] = 'Вы указали некорретный шаг ставки';
      } else {
          $valid_data['lot-step'] = $_POST['lot-step'];
      };
    } else {
        $error_list['lot-step'] = 'Вы не указали шаг торгов';
    };

    if ($_POST['lot-date']) {
      $pushed_date = strtotime($_POST['lot-date']);
      $curr_date = strtotime('now');
      if ($pushed_date < $curr_date) {
          $error_list['lot-date'] = 'Вы указали дату из прошлого';
      } else {
          $valid_data['lot-date'] = $_POST['lot-date'];
      }
    };

    if (isset($_POST['lot-name'])) {
      $valid_data['lot-name'] = beauty_characters($_POST['lot-name']);
    };

    if (isset($_POST['message'])) {
      $valid_data['message'] = beauty_characters($_POST['message']);
    };


    if (is_uploaded_file($_FILES['lot-photo']['tmp_name'])) {
        if ($_FILES['lot-photo']['type'] != 'image/png' and $_FILES['lot-photo']['type'] != 'image/jpeg') {
            $error_list['lot-photo'] = 'Вы загрузили изображение не подходящего формата.';
        } else {
        $file_name = $_FILES['lot-photo']['name'];
        $file_path = __DIR__ . '/uploads/';
        move_uploaded_file($_FILES['lot-photo']['tmp_name'], $file_path.$file_name);
        $lotSchema['src'] = '/uploads/'. $file_name;
        };
    } else {$error_list['lot-photo'] = 'Вы не выбрали фотографию.';};

    if (count($error_list)) {
        foreach ($_POST as $key => $value) {
            if ($key != 'category') {
                $error_savings[$key] = $_POST[$key];
            } else {
                $error_savings['catErr'] = $_POST['category'];
            };
        };
        $error_list['form_class'] = 'form--invalid';
        $error_list['input_class'] = 'form__item--invalid';
        $content = render_template('templates/add-lot.php', ['error_list' => $error_list,
            'error_savings' => $error_savings
            ]);
        renderPage('templates/layout.php', ['content' => $content,
            'title' => 'Ошибка отправки формы',
            'user_name' => 'Гость',
            'user_avatar' => 'img/user.jpg',
            'category' => ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'],
            'is_auth' => (boolean) rand(0, 1)
            ]);
    } else {
        $lotSchema['title'] = $valid_data['lot-name'];
        $lotSchema['category'] = $valid_data['category'];
        $lotSchema['price'] = $valid_data['lot-rate'];
        $lotSchema['description'] = $valid_data['message'];
        $content = render_template('templates/lot.php', ['lot' => $lotSchema]);
        renderPage('templates/layout.php', ['content' => $content,
            'title' => 'Успешно добавлен лот',
            'user_name' => 'Гость',
            'user_avatar' => 'img/user.jpg',
            'category' => ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'],
            'is_auth' => (boolean) rand(0, 1)]);
    };
};

$content = render_template('templates/add-lot.php', []);
renderPage('templates/layout.php', ['content' => $content,
    'title' => 'Добавление лота',
    'user_name' => 'Гость',
    'user_avatar' => 'img/user.jpg',
    'category' => ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'],
    'is_auth' => (boolean) rand(0, 1)
]);
