<?php

require('class.login.php');

session_start();

$message = '';

if (isset($_SESSION['user_id'])) {
    header('location:index.php');
}

if (isset($_POST["register"])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if ($username == "" || $password == "") {
        $message = "Username and Password are required.";
    } elseif ($password != $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        $login = new Login();
        $message = $login->AddUser($username, $password);
        if ($message == "Registration Completed") {

        }
    }
}
?>

<html>
<head>
    <title>Chat Application using PHP Ajax Jquery</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
<div class="container">
    <br />

    <h3 align="center">Chat Application using PHP Ajax Jquery</a></h3><br />
    <br />
    <div class="panel panel-default">
        <div class="panel-heading">Chat Application Register</div>
        <div class="panel-body">
            <form method="post">
                <span class="text-danger"><?php echo $message; ?></span>
                <div class="form-group">
                    <label>Enter Username</label>
                    <input type="text" name="username" class="form-control" />
                </div>
                <div class="form-group">
                    <label>Enter Password</label>
                    <input type="password" name="password" class="form-control" />
                </div>
                <div class="form-group">
                    <label>Re-enter Password</label>
                    <input type="password" name="confirm_password" class="form-control" />
                </div>
                <div class="form-group">
                    <input type="submit" name="register" class="btn btn-info" value="Register" />
                </div>
                <div align="center">
                    <a href="login.php">Login</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
