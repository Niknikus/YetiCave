<?php
session_start();
require_once 'functions.php';
require_once 'userdata.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $content_page = render_template('templates/login.php', []);
    renderPage('templates/layout.php', ['content' => $content_page,
        'title' => 'Вход на сайт',
        'category' => ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное']
]);
} else {
    $form = $_POST;
    $error_list = [];
    $error_vars = [];

    if (!isset($form['email'])) {
        $error_list['email'] = 'Вы не заполнили email.';
    } else {
      if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
          $error_list['email'] = 'Вы указали некоррктный формат email';
          $error_vars['email'] = beauty_characters($form['email']);
      } else {
          $user = searchUsersByEmail($form['email'], $users);
          if (!$user) {
              $error_list['email'] = 'Такого email нет в зарегистрированных пользователях';
              $error_vars['email'] = beauty_characters($form['email']);
          };
      };
    };

    if (!isset($form['password'])) {
        $error_list['password'] = 'Вы не ввели пароль';
    } else {
        if ($user) {
            if (!password_verify($form['password'], $user['password'])) {
                $error_list['password'] = 'Вы ввели неверный пароль';
                $error_vars['email'] = $form['email'];
            } else {
                $sess_name = 'is_auth';
                $sess_user = ['user_name' => $user['name'], 'user_avatar' => 'img/user.jpg'];
                $_SESSION[$sess_name] = $sess_user;
            };
        };
    };

    if (count($error_list)) {
        $error_list['class_invalid'] = 'form__item--invalid';
        $error_list['form_invalid'] = 'form--invalid';
        $content_page = render_template('templates/login.php', ['error_list' => $error_list, 'error_vars' => $error_vars]);
        renderPage('templates/layout.php', ['content' => $content_page,
            'title' => 'Ошибка авторизации',
            'category' => ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'],
            ]);
    } else {
        header('Location: index.php');
    };
};
