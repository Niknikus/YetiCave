<?php
require_once "functions.php";
session_start();

// Запрет для неавторизованных пользователей.
if (!isset($_SESSION["is_auth"])) {
   http_response_code(403);
   exit();
};
$user_id = $_SESSION["is_auth"]["uid"];
$category = get_category($link);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form = $_POST;

    // Массивы для данных при ошибках
    $error_list = [];
    $error_savings = [];

    // Словарь для категорий в БД
    $cat_dict = ["Доски и лыжи"	=> "boards",
        "Ботинки" => "boots",
        "Одежда" => "clothing",
        "Крепления" => "fasting",
        "Разное" => "other",
        "Инструменты" => "tools"
    ];

    // Проверка полей.
    if ($form["category"] == "Выберите категорию") {
        $error_list["category"] = "Вы не выбрали категорию товара.";
        $error_savings["category"] = $form["category"];
    } else {
        $error_savings["category"] = $form["category"];
        $category = $cat_dict[$form["category"]];
    };

    // Проверка цены, не должна быть меньше ноля.
    if (isset($form["lot-rate"])) {
      if (!is_numeric($form["lot-rate"]) or $form["lot-rate"] <= 0) {
          $error_list["lot-rate"] = "Указали невозможную цену лота";
          $error_savings["lot-rate"] = $form["lot-rate"];
      } else {
          $curr_price = $form["lot-rate"];
          $error_savings["lot-rate"] = $form["lot-rate"];
      };
    } else {
        $error_list["lot-rate"] = "Вы не указали цену лота";
    };

    if (isset($form["lot-step"])) {
      if (!is_numeric($form["lot-step"]) or $form["lot-step"] <= 0) {
          $error_list["lot-step"] = "Вы указали некорретный шаг ставки";
          $error_savings["lot-step"] = $form["lot-step"];
      } else {
          $step = $form["lot-step"];
          $error_savings["lot-step"] = $form["lot-step"];
      };
    } else {
        $error_list["lot-step"] = "Вы не указали шаг торгов";
    };

    if (isset($form["lot-date"])) {
      $form_date = strtotime($form["lot-date"]);
      $now_date = strtotime("now");
      if ($form_date < $now_date) {
          $error_list["lot-date"] = "Вы указали дату из прошлого";
          $error_savings["lot-date"] = $form["lot-date"];
      } else {
          $expire_date = strtotime($form["lot-date"]);
          $error_savings["lot-date"] = $form["lot-date"];
      };
    };

    if (isset($form["lot-name"])) {
      $title = beauty_characters($form["lot-name"]);
      $error_savings["lot-name"] = beauty_characters($form["lot-name"]);
    };

    if (isset($form["message"])) {
      $description = beauty_characters($form["message"]);
      $error_savings["message"] = beauty_characters($form["message"]);
    };

    // Обработка файла

    if (is_uploaded_file($_FILES["lot-photo"]["tmp_name"])) {
        if ($_FILES["lot-photo"]["type"] != "image/png" and $_FILES["lot-photo"]["type"] != "image/jpeg") {
            $error_list["lot-photo"] = "Вы загрузили изображение не подходящего формата.";
        } else {
        $file_name = $_FILES["lot-photo"]["name"];
        $file_path = __DIR__ . "/img/lots/";
        move_uploaded_file($_FILES["lot-photo"]["tmp_name"], $file_path . $file_name);
        $img = "img/lots/". $file_name;
        };
    } else {$error_list["lot-photo"] = "Вы не выбрали фотографию.";};

    // Если есть ошибки

    if (count($error_list)) {
        $error_list["form_class"] = "form--invalid";
        $error_list["input_class"] = "form__item--invalid";
        $content = render_template("templates/add-lot.php", ["error_list" => $error_list,
            "error_savings" => $error_savings
            ]);
        renderPage("templates/layout.php", ["content" => $content,
            "title" => "Ошибка отправки формы",
            "category" => $category
            ]);
    } else {
        // Добавление лота в БД
        $add_time = strtotime("now");
        $addition = add_lot($link, $title, $description, $img, $add_time, $expire_date, $category, $user_id, $curr_price, $step);
        if ($addition) {
            header("Location: lot.php?id=" . $addition);
        } else {
            query_error("Не удалось добавить ваш лот. Попробуйте позже.");
        };
        $link->close();
    };
};

$content = render_template("templates/add-lot.php", []);
renderPage("templates/layout.php", ["content" => $content,
    "title" => "Добавление лота",
    "category" => $category
]);
