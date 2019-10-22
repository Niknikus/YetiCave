<?php
require_once "functions.php";
date_default_timezone_set("Europe/Moscow");
session_start();
$category = get_category($link);

if ($_SERVER["REQUEST_METHOD" != "POST"]) {
    header("Location: login.php");
} else {
    $id_lot = +$_GET["id"];
    $id_user = +$_SESSION["is_auth"]["uid"];
    $bet = +$_POST["cost"];
    if (Bet::check_bet($link, $id_lot, $bet)) {
        Bet::add_bet($link, strtotime("now"), $bet, $id_lot, $id_user);
    } else {
        query_error("Ставка меньше минимальной!");
    };
}
