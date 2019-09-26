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
    $zero_H = 24;
    $zero_M = 60;
    $result = ($zero_H - $hour_c) . ':' . ($zero_M - $min_c);
    print($result);
}
