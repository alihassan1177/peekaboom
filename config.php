<?php

if (session_status() == PHP_SESSION_NONE) {
    $id = session_start();
}

$conn = mysqli_connect('localhost', 'root', '', 'peekaboom');
