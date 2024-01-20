<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {

    require_once "config.php";

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $status = false;

    $sql = "INSERT INTO `users`(`status`, `name`, `password`, `email`) VALUES ('$status','$name','$password','$email')";
    try {
        if (mysqli_query($conn, $sql)) {
            header('location:login.php');
        }
    } catch (\Exception $e) {
        echo $e->getMessage();
    }

}else{
    header('location:register.php');
}