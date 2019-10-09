<?php
function render_template($rout_to_template, $data) {
    ob_start();
    extract($data);
    require_once $rout_to_template;
    return ob_get_clean();
}
function renderPage($rout_to_layout, $data) {
    ob_start();
    extract($data);
    require_once $rout_to_layout;
    $layout = ob_get_clean();
    print($layout);
}
function price_format($integer) {
    $new_price = ceil($integer);
    if ($integer < 1000) {
        $new_price = (string) $new_price;
        return $new_price . ' ' . '&#8381;';
    }
    else {
        $new_price = number_format($new_price, 0, ',', ' ');
        $new_price = (string) $new_price;
        return $new_price . ' ' . '&#8381;';
    }
}
function time_to_midnight() {
    date_default_timezone_set('Europe/Moscow');
    $curr_date = strtotime('now');
    $hour_c = date('H', $curr_date);
    $min_c = date('i', $curr_date);
    $zero_H = 23;
    $zero_M = 60;
    $result = ($zero_H - $hour_c) . ':' . ($zero_M - $min_c);
    print($result);
}
function add_id_file($startIndex, $fileName, $postFix, $array) {
    $index = $startIndex;
    $new_array = $array;
    foreach ($array as $item => $value) {
        $new_array[$index-1]['root'] = $fileName . $postFix . '?' . 'id=' . $index ;
        $index += 1;
    }
    return $new_array;
}
function beauty_characters($text) {
    $text = trim($text);
    $text = stripcslashes($text);
    $text = strip_tags($text);
    $text = htmlspecialchars($text);
    return $text;
}

function path_params_compiler($array) {
    $out = '';
    foreach ($array as $item => $value) {
        $out .= $item . '=' . $value . '&';
    }
    return $out;
}

function searchUsersByEmail($email, $array) {
    $user = 0;
    foreach ($array as $item => $value) {
        if ($email == $array[$item]['email']) {
            $user = $array[$item];
        };
    };
    return $user;
}
