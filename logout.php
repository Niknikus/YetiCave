<?php
session_start();
$_SESSION = [];
foreach ($_COOKIE as $key => $value) {
    setcookie($key, '', $_SERVER['REQUEST_TIME'] - 3600);
};
header('Location: index.php');
