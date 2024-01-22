<?php

require_once __DIR__ . '/vendor/autoload.php';

use Josantonius\Cookie\Cookie;

if (session_status() == PHP_SESSION_NONE) {
    $id = session_start();
}

$cookie = new Cookie();

$conn = mysqli_connect('localhost', 'root', '', 'peekaboom');
