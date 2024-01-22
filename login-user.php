<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_user'])) {

    require_once "config.php";

    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $result = mysqli_query($conn, "SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$password'");
        if ($result->num_rows) {
            $user = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $user = $user[0];
            $user_id = $user['id'];

            $query = mysqli_query($conn, "SELECT * FROM `user_sessions` WHERE `user_id` = '$user_id' AND `expired_at` IS NULL");
            
            if ($query->num_rows) {
                echo "User is already logged in";
                return;
            }

            $_SESSION['user'] = $user;
            $cookie->set('user', $user['id']);
            $current_time = time();

            mysqli_query($conn, "INSERT INTO `user_sessions`(`user_id`, `created_at`) VALUES ($user_id, '$current_time')");
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
