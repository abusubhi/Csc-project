<?php

$st_id = $_POST['st_id'];
$course_id = $_POST['course_id'];
$mark = $_POST['mark'];

$sql = "UPDATE student_course SET mark=? WHERE student_id=? AND course_id=?";

$conn = mysqli_connect("localhost", "root", "", "school");
$stmt = mysqli_stmt_init($conn);

if (!$stmt->prepare($sql)) {
    $result['msg'] = ["sql error"];
    $result['status'] = false;
    echo json_encode($result);
    exit;
} else {
    try {
        $stmt->bind_param('iii', $mark, $st_id, $course_id);
        $stmt->execute();
    } catch (\Throwable $th) {
        $result['msg'] = ["sql error"];
        $result['status'] = false;
        echo json_encode($result);
        exit;
    }
    $result['status'] = true;
    echo json_encode($result);
}
