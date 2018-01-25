<?php

require_once ('config.php');

$conn = mysqli_connect(SERVER_NAME, SERVER_USER, SERVER_PASS, SERVER_DB);

if (!$conn) {
    die();
}

session_start(); 
        

function translate($string)
{
    return $string;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

