<?php
session_start();
if (isset($_SESSION['email'])) {
    header("Location: dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body style="background-color:#CCCCFF;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="text-center">Sign Up</h1>
                <form method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="password_confirm">Confirm Password</label>
                        <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Confirm Password">
                    </div>
                    <button type="submit" class="btn btn-primary">Sign Up</button>
                </form>
                <strong id="error-msg" class="mt-3" style="color:red"></strong>
                <p class="mt-3">Already have an account? <a href="login.php">Log In</a></p>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        $("form").submit(function(event) {
            event.preventDefault();
            var username = $("#username").val();
            
            var email = $("#email").val().toLowerCase();
            var password = $("#password").val();
            var password_confirm = $("#password_confirm").val();
            // primitive validation
            if (username == "" || email == "" || password == "" || password_confirm == "") {
                $("#error-msg").html("Please fill out all fields");
                return;
            } else if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                $("#error-msg").html("Please enter a valid email address");
                return;
            } else if (password != password_confirm) {
                console.log(password, password_confirm);
                $("#error-msg").html("Passwords do not match");
                return;
            } else if (!password.match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/)) {
                $("#error-msg").html("Password must be at least 8 characters long and contain at least one number, one uppercase letter, one lowercase letter, and one special character");
                return;
            } else if (!username.match(/^[a-zA-Z0-9]*$/)) {
                $("#error-msg").html("Only letters and numbers allowed for the username");
                return;
            } else {
                $.ajax({
                    url: "./controllers/signup_check.php",
                    method: "POST",
                    data: {
                        'email': email,
                        'username': username,
                        'password': password,
                        'password_confirm': password_confirm,
                    }
                }).done(function(data) {
                    console.log(data);
                    res = JSON.parse(data);
                    if (res['status']) { // if sign up successful, redirect user to the login.php page.
                        window.location = "login.php";
                    } else {
                        var errorMessage = '';
                        // if there are any errors, display them
                        console.log(res.msg);
                        $.each(res['msg'], function(index, message) {
                            errorMessage += '<div>' + message + '</div>';
                        });
                        $("#error-msg").html(errorMessage);
                    }
                });
            }
        });
    </script>
</body>

</html>