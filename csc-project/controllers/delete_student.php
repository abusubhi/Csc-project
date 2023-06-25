<?php
$id = $_POST['st_id'];

$sql = "DELETE FROM students WHERE student_id=?";
$conn = mysqli_connect("localhost", "root", "", "school");
$stmt = mysqli_stmt_init($conn);

if (!$stmt->prepare($sql)) {
    $result['msg'] = ["sql error"];
    $result['status'] = false;
    echo json_encode($error);
    exit;
} else {
    try {
        $stmt->bind_param('i', $id);
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
