<?php

$error = array();
$resp = array();




// if get request is sent to display error message
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $resp['msg'] = ["forbidden method"];
    $resp['status'] = false;
    echo (json_encode($resp['msg']));
    exit;
}


/* server side validation */
$username = ($_POST['username']);
$email = strtolower($_POST['email']);
$password = ($_POST['password']);
$password_confirm = ($_POST['password_confirm']);

if (empty($_POST['email']))
    $error[] = "email field is required";

else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    $error[] = "Please enter a valid email address";

else if (empty($_POST['username']))
    $error[] = "username field is required";

else if (!preg_match("/^[a-zA-Z0-9]*$/", $username))
    $error[] = "Only letters and numbers allowed";

else if (empty($_POST['password']) || empty($_POST['password_confirm']))
    $error[] = "Password field is required";

else if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", $password))
    $error[] = "password must be at least 8 characters long and contain at least one number, one uppercase letter, one lowercase letter, and one special character";

else if ($password != $password_confirm)
    $error[] = "passwords do not match";


if (count($error) > 0) {
    $resp['msg'] = $error;
    $resp['status'] = false;
    echo json_encode($resp);
    exit;
} else {
    $password = md5($password_confirm);

    /* connect to database and insert data */
    $sql = "INSERT INTO students (student_name, student_email, password) VALUES (?, ?, ?)";
    $conn = mysqli_connect("localhost", "root", "", "school");
    $stmt = mysqli_stmt_init($conn);


    

    if (!$stmt->prepare($sql)) {
        // check if there is any error in our sql statement
        $resp['msg'] = ["Internal Server Error" . mysqli_error($conn)];
        $resp['status'] = false;
        echo json_encode($resp);
        exit;
    } else {
        // if no error then insert data to database
        $stmt->bind_param("sss", $username, $email, $password);
        try {
            $stmt->execute();
        } catch (\Throwable $th) {
            if ($stmt->errno == 1062) {
                $resp['msg'] = ["Email already exists"];
                $resp['status'] = false;
                echo json_encode($resp);
            } else {
                $resp['msg'] = ["Internal Server Error  " . $stmt->error . $stmt->errno];
                $resp['status'] = false;
                echo json_encode($resp);
            }
            exit;
        }
        $resp['msg'] = ["success"];
        $resp['status'] = true;
        echo json_encode($resp);
        exit;
    }
}
