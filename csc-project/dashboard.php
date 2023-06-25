<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-color:red;">
        <div class="container">
            <a class="navbar-brand" href="#">School CSC</a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="btn btn-secondary">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
    <?php

    function check_activity($email)
    {
        $sql = "SELECT account_status FROM students WHERE student_email='$email'";
        $conn = mysqli_connect("localhost", "root", "", "school");
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['account_status'];
    }
    SESSION_START();

    // If the user is not logged in, redirect to login.php
    if (!isset($_SESSION['email'])) {
        header("Location: login.php");
    } else {
        // User is logged in, check if admin or user
        if (isset($_SESSION['admin'])) {
            include 'views/admin_dashboard.php';
        } else {
            echo '<h1 class="mt-4 text-center">Student Portal</h1>';

            echo "<h3 class='mt-4'>Welcome " . $_SESSION['username'] . "!</h3>";
            // Check if the user is active or not
            if (check_activity($_SESSION['email']) == 0) {
                echo "<p class='mt-4'>Your account is not active</p>";
                include './views/user_inactive.html';
            } else {
                echo "<p class='mt-4'>Your account is active! Yay! </p>";
                // Import the partial HTML file
                include './views/user_active.php';
            }
        }
    }

    ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>