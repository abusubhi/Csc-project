<?php
$error = array();
$resp = array();

// make sure request os not get
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $resp['msg'] = ["forbidden method"];
    $resp['status'] = false;
    echo (json_encode($resp['msg']));
    exit;
}

// backend validation
if (empty($_POST['email'])) {
    $error[] = "username field is required";
}

if (empty($_POST['password'])) {
    $error[] = "Password field is required";
}

if (count($error) > 0) {
    $resp['msg'] = $error;
    $resp['status'] = false;
    echo json_encode($resp);
    exit;
} else {

    $username = $_POST['email'];
    $password = md5($_POST['password']);

    // check if username and password match
    $sql = "SELECT * FROM students WHERE student_email=? AND password=?";
    $conn = mysqli_connect("localhost", "root", "", "school");
    $stmt = mysqli_stmt_init($conn);

    if (!$stmt->prepare($sql)) {
        // check if there is any error in our sql statement
        $resp['msg'] = ["Internal Server Error" . $stmt->error];
        $resp['status'] = false;
        echo json_encode($resp);
        exit;
    } else {
        // prepare and execute sql statement
        try {
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
        } catch (Exception $e) {
            $resp['msg'] = ["Internal Server Error" . $stmt->error . $stmt->errno];
            $resp['status'] = false;
            echo json_encode($resp);
            exit;
        }
        $result = $stmt->get_result();
        if (mysqli_num_rows($result) > 0) {
            $resp['status'] = true;
            session_start();
            $row = mysqli_fetch_assoc($result);
            $_SESSION['username'] = $row['student_name'];
            $_SESSION['email'] = $row['student_email'];
            $_SESSION['user_id'] = $row['student_id'];

            if ($row['account_status'] == 1) {
                // check if status in database is true
                $resp["msg"] = ["success"];
                echo json_encode($resp);
                exit;
            } else {
                $resp['msg'] = ["Your account is not active"];
                echo json_encode($resp);
                exit;
            }
        } else {
            // check if is admin
            $sql = "SELECT * FROM admins WHERE email=? AND password=?";
            $stmt = mysqli_stmt_init($conn);
            if (!$stmt->prepare($sql)) {
                // check if there is any error in our sql statement
                $resp['msg'] = ["Internal Server Error" . $stmt->error];
                $resp['status'] = false;
                echo json_encode($resp);
                exit;
            } else {
                // prepare sql statement
                $stmt->bind_param("ss", $username, $password);
                try {
                    $stmt->execute();
                } catch (Exception $e) {
                    $resp['msg'] = ["Internal Server Error" . $stmt->error . $stmt->errno];
                    $resp['status'] = false;
                    echo json_encode($resp);
                    exit;
                }
                $result = $stmt->get_result();
                if (mysqli_num_rows($result) > 0) {
                    $resp['status'] = true;
                    session_start();
                    $row = mysqli_fetch_assoc($result);
                    $_SESSION['username'] = $row['admin_name'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['user_id'] = $row['admin_id'];
                    $_SESSION['admin'] = 1;
                    $resp["msg"] = ["success"];
                    echo json_encode($resp);
                    exit;
                } else {

                    $resp['status'] = false;
                    $resp['msg'] = ["Username or password is incorrect"];
                    echo json_encode($resp);
                    exit;
                }
            }
        }
    }
}
