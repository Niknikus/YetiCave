<?php
session_start();
require_once "functions.php";
$category = get_category($link);

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $content_page = render_template("templates/login.php", []);
    renderPage("templates/layout.php", ["content" => $content_page, "title" => "Вход на сайт", "category" => $category]);
} else {
    $form = $_POST;
    $error_list = [];
    $error_vars = [];

    if (!isset($form["email"])) {
        $error_list["email"] = "Вы не заполнили email.";
    } else {
      if (!filter_var($form["email"], FILTER_VALIDATE_EMAIL)) {
          $error_list["email"] = "Вы указали некорректный формат email";
          $error_vars["email"] = beauty_characters($form["email"]);
      } else {
          $result = get_user_by_email($link, $form["email"]);
          if ($result->num_rows == 0) {
              $error_list["email"] = "Такого email нет в зарегистрированных пользователях";
              $error_vars["email"] = beauty_characters($form["email"]);
          } else {
              $user = $result->fetch_assoc();
          };
      };
    };

    if (!isset($form["password"])) {
        $error_list["password"] = "Вы не ввели пароль";
    } else {
        if ($user) {
            if (!password_verify($form["password"], $user["password"])) {
                $error_list["password"] = "Вы ввели неверный пароль";
                $error_vars["email"] = $form["email"];
            } else {
                $sess_name = "is_auth";
                $sess_user = ["user_name" => $user["name"], "user_avatar" => $user["img"]];
                $_SESSION[$sess_name] = $sess_user;
            };
        };
    };

    if (count($error_list)) {
        $error_list["class_invalid"] = "form__item--invalid";
        $error_list["form_invalid"] = "form--invalid";
        $content_page = render_template("templates/login.php", ["error_list" => $error_list, "error_vars" => $error_vars]);
        renderPage("templates/layout.php", ["content" => $content_page, "title" => "Ошибка авторизации", "category" => $category]);
        $link->close();
    } else {
        header("Location: index.php");
        $link->close();
    };
};
