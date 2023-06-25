

<?php

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$status = $_POST['status'] == 'active' ? 1 : 0;

$sql = "UPDATE students SET student_name=?, student_email=?, account_status=? WHERE student_id=?";
$conn = mysqli_connect("localhost", "root", "", "school");
$stmt = mysqli_stmt_init($conn);

if (!$stmt->prepare($sql)) {
    $result['msg'] = ["sql error"];
    $result['status'] = false;
    echo json_encode($error);
    exit;
} else {
    try {
        $stmt->bind_param('ssii', $name, $email, $status, $id);
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
