<?php
if (isset($_SESSION['email'])) {
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body style="background-color:#CCCCFF;">
    <div class="container" >
        <h1>Login</h1>
        <form method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary">Log In</button>
        </form>
        <strong id="error-msg" style="color:red"></strong>
        <br>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $("form").submit(function(event) {
                event.preventDefault();
                var email_input = $("#email");
                var password_input = $("#password");

                var email = email_input.val();
                var password = password_input.val();
                var error = false;

                if (email == "") {
                    email_input.addClass("is-invalid");
                    error = true;
                }
                if (password == "") {
                    password_input.addClass("is-invalid");
                    error = true;
                }
                if (error) {
                    return;
                } else {
                    $.ajax({
                        url: "./controllers/login_check.php",
                        method: "POST",
                        data: {
                            'email': email,
                            'password': password,
                        }
                    }).done(function(data) {
                        console.log(data);

                        resp= JSON.parse(data);
                        if (resp['status']) {
                            window.location = "dashboard.php";
                        } else {
                            var errorMessage = '';
                            console.log(resp.msg);
                            $.each(resp['msg'], function(index, message) {
                                errorMessage += '<div>' + message + '</div>';
                            });
                            $("#error-msg").html(errorMessage);
                        }
                    });
                }
            });
        });
    </script>

</body>

</html>