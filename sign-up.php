<?php
session_start();
require_once "functions.php";
$category = get_category($link);

// Проверям метод запроса, если get то:
if ($_SERVER['REQUEST_METHOD'] != "POST") {
    $content_page = render_template("templates/sign-up.php", []);
    renderPage("templates/layout.php", ["content" => $content_page, "title" => "Регистрация", "category" => $category]);
// Если же post, то начинаем обрабатывать форму
} else {
    $form = $_POST;
    $file = $_FILES;
    $required = ["email", "password", "name", "message"];
    $error_list = [];
    $user_savings = [];
    $error_dict = ["email" => "Вы не указали свой email.",
        "password" => "Вы не указали свой пароль.",
        "name" => "Вы не написали своё имя.",
        "message" => "Вы не указали, как с вами можно связаться."];
    foreach ($required as $key) {
        if (!$form[$key]) {
            $error_list[$key] = $error_dict[$key];
        };
    };

    // Если прислали пустую форму, сразу отправляем на исправление.
    if (count($error_list) > 0) {
        $error_list["form_class"] = "form--invalid";
        $error_list["input_class"] = "form__item--invalid";
        $content_page = render_template("templates/sign-up.php", ["error_list" => $error_list, "user_savings" => $user_savings]);
        renderPage("templates/layout.php", ["content" => $content_page, "title" => "Ошибка регистрации", "category" => $category]);
    };

    // Если нет, то проверяем данные.

    // Проверяем что почта корректная и что такой уже нет в базе.
    if (!filter_var($form["email"], FILTER_VALIDATE_EMAIL)) {
        $error_list["email"] = "Вы указали некорректный email";
        $user_savings = user_savings($form);
    } else {
        $result = get_user_by_email($link, $form["email"]);
        if ($result->num_rows > 0) {
            $error_list["email"] = "У нас уже есть пользователь с такой почтой.";
            $user_savings = user_savings($form);
        };
    };
// Смотрим, есть ли картинка для аватарки.

    if ($file["user-avatar"]["tmp_name"]) {

        // Проверяем формат изображения. Можно только png, jpeg и gif

        if ($file["user-avatar"]["type"] != "image/png" and $file["user-avatar"]["type"] != "image/jpeg" and $file["user-avatar"]["type"] != "image/gif") {
            $error_list["file"] = "Вы загрузили аватарку неподходящего формата.";
        };

        // Если изображение нужного формата, и других ошибок нет, добавляем изображение в папку
        if (count($error_list) == 0) {
            $file_name = $file["user-avatar"]["name"];
            $file_path = __DIR__ . "/img/users/";
            move_uploaded_file($file["user-avatar"]["tmp_name"], $file_path . $file_name);
            // Сразу возвращаем переменную с адресом изображения
            $user_img = "img/users/" . $file_name;
            $user_savings["file"] = $user_img;
        };
    }

    // Добавление пользователя, либо отправка на изменение email.

    if (count($error_list) > 0) {
        $error_list["form_class"] = "form--invalid";
        $error_list["input_class"] = "form__item--invalid";
        $content_page = render_template("templates/sign-up.php", ["error_list" => $error_list, "user_savings" => $user_savings]);
        renderPage("templates/layout.php", ["content" => $content_page, "title" => "Ошибка регистрации", "category" => $category]);
    } else {
        $curr_date = strtotime('now');
        $name = beauty_characters($form["name"]);
        $email = beauty_characters($form["email"]);
        $img = $user_img ? $user_img : "img/avatar.jpg";
        $password = Password::hash($form["password"]);
        $contacts = $form["message"];
        $result = add_user($link, $curr_date, $name, $email, $img, $password, $contacts);
        if (!$result) {
            $content_page = render_template("templates/error-query.php", ["header" => "Невозможно зарегистрироваться.",
                "errors" => "Ошибка подключения к базе данных."]);
            renderPage("templates/layout.php", ["content" => $content_page, "title" => "Ошибка регистрации", "category" => $category]);
            $link->close();
        } else {
            $link->close();
            header("Location: login.php");
        };
    };

};
