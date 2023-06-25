<?php
$sql = "SELECT * FROM courses";
$conn = mysqli_connect("localhost", "root", "", "school");
$stmt = mysqli_stmt_init($conn);

if (!$stmt->prepare($sql)) {
    $error['msg'] = ["sql error"];
    $error['status'] = false;
    echo json_encode($error);
    exit;
} else {
    try {
        $stmt->execute();
    } catch (\Throwable $th) {
        $error['msg'] = ["sql error"];
        $error['status'] = false;
        echo json_encode($error);
        exit;
    }
    $result = $stmt->get_result();
    $result = ($result->fetch_all(MYSQLI_ASSOC));

    echo json_encode($result);
    exit;
}
