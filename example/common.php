<?php

session_start();
require '../vendor/autoload.php';

function url($route)
{
    $current_url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    $current_dir = pathinfo($current_url)['dirname'] . '/';
    $query_string = ( $_SERVER['QUERY_STRING'] !== '' ) ? '?' . $_SERVER['QUERY_STRING'] : '';

    return $current_dir . $route . $query_string;
}

