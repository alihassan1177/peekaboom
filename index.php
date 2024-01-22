<?php

require_once "config.php";
require_once "confirm-auth.php";

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['logout'])) {
    session_destroy();
    $user_id = $user['id'];
    $expiry_time = time();
    $cookie->remove('user');
    mysqli_query($conn, "UPDATE `user_sessions` SET `expired_at`='$expiry_time' WHERE `user_id` = $user_id");
    header("location:login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Peekaboom</title>
    <link rel="shortcut icon" href="/assets/images/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body class="bg-light">

    <div class="container">
        <div style="min-height: 100vh;" class="d-flex align-items-center justify-content-center">
            <div>
                <h1>Hello <?= $user['name'] ?></h1>
                <form action="" method="post">
                    <button class="btn btn-sm btn-danger" name="logout">Logout</button>
                </form>
            </div>

        </div>
    </div>

    <script src="/assets/js/bootstrap.js"></script>

    <script>
        const conn = new WebSocket('ws://localhost:8080/?id=<?= $user['id'] ?>&access_token=45151')

        conn.addEventListener('open', (event) => {
            console.log('CONNECTED')
        })

        conn.addEventListener('message', (event) => {
            console.log(event.data)
        })

        conn.addEventListener('close', () => {
            console.log("CONNECTION CLOSED")
        })
    </script>


</body>

</html>