<?php

$name = $_POST['name'];
$pass_mark = $_POST['pass_mark'];

$sql = "INSERT INTO courses (course_name, pass_mark) VALUES (?,?)";
$conn = mysqli_connect("localhost", "root", "", "school");
$stmt = mysqli_stmt_init($conn);

if (!$stmt->prepare($sql)) {
    $result['msg'] = ["sql error"];
    $result['status'] = false;
    echo json_encode($error);
    exit;
} else {
    try {
        $stmt->bind_param('si', $name, $pass_mark);
        $stmt->execute();
    } catch (\Throwable $th) {
        $result['msg'] = ["sql error"];
        $result['status'] = false;
        echo json_encode($error);
        exit;
    }
    $result['status'] = true;
    echo json_encode($result);
}
