<?php
include_once './db.php';
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $login = authentication($username, $password);

    if (!empty($login) && isset($login->username) && $login->username == $username && $login->password == $password) {
        $_SESSION['user_id'] = $login->user_id;
        header('Location:dashboard.php?view=add');
    } else {
        header('Location:index.php');
        echo '<script>alert("Invalid Username and Password");<script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>FreshBox Media - Kaam Ka Dabba</title>

        <!-- CSS -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/form-elements.css">
        <link rel="stylesheet" href="assets/css/style.css">


    </head>

    <body>

        <!-- Top content -->
        <div class="top-content">
            <section class="logo"> <a href=""><img src="assets/img/Untitled-2.png" alt="Taleview Logo"></a></section>
            <div class="inner-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 text">                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">
                            <div class="form-top">
                                <div class="form-top-left">
                                    <h3>Login to our site</h3>
                                    <p>Enter your username and password to log on:</p>
                                </div>
                                <div class="form-top-right">
                                    <i class="fa fa-lock"></i>
                                </div>
                            </div>
                            <div class="form-bottom">
                                <form class="login-form" method="post" action="index.php">
                                    <div class="form-group">
                                        <label class="sr-only" for="form-username">Username</label>
                                        <input type="text" name="username" placeholder="Username..." class="form-username form-control" id="form-username">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="form-password">Password</label>
                                        <input type="password" name="password" placeholder="Password..." class="form-password form-control" id="form-password">
                                    </div>
                                    <button type="submit" name="submit" class="btn" onclick="window.location.href = 'dashboard.html';">Log In</button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>        
        <!--[if lt IE 10]>
            <script src="assets/js/placeholder.js"></script>
        <![endif]-->
    </body>
</html>