<?php

if (session_status() == PHP_SESSION_NONE || !isset($_SESSION['user'])) {
    header('location:login.php');
}