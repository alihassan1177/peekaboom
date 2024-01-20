<?php

require_once "config.php";

if (isset($_SESSION['user'])) {
    header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User - Peekaboom</title>
    <link rel="shortcut icon" href="/assets/images/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body class="bg-light">

    <div class="container">
        <div style="min-height: 100vh;" class="d-flex align-items-center justify-content-center">
            <div style="width: 100%; max-width : 500px;" class="bg-white rounded shadow-sm p-4">
                <h4 class="mb-4">Sign in to your account</h4>
                <form action="login-user.php" class="row g-3" method="post">

                    <div class="col-12">
                        <label class="form-label" for="email">Email</label>
                        <input placeholder="Enter your email" type="text" class="form-control" name="email" id="email">
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="password">Password</label>
                        <input placeholder="Enter your password" type="password" class="form-control" name="password" id="password">
                    </div>

                    <div class="col-12">
                        <button name="login_user" class="btn btn-primary">
                            Login
                        </button>
                    </div>
                    <div class="col-12">
                        <a class="text-decoration-none" href="/register.php">Create new account</a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="/assets/js/bootstrap.js"></script>

</body>

</html>