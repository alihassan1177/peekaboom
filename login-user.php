<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_user'])) {

    require_once "config.php";

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$password'";

    try {
        $result = mysqli_query($conn, $sql);
        if ($result->num_rows) {
            $user = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $_SESSION['user'] = $user[0];
            header('location:index.php');
        } else {
            echo "User credentials not correct";
        }
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
} else {
    header('location:login.php');
}
