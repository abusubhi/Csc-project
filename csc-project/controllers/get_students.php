<?php

$num = $_POST['num'];
$id = $_POST['st_id'];

// if get request is sent to display result message


if ($_POST['st_id'] == null) {
    if ($num == null) {
        $sql = "SELECT student_id, student_name, student_email, account_status FROM students";
    } else {
        $sql = "SELECT student_id, student_name, student_email, account_status FROM students limit $num";
    }

    $conn = mysqli_connect("localhost", "root", "", "school");
    $stmt = mysqli_stmt_init($conn);

    if (!$stmt->prepare($sql)) {
        $result['msg'] = ["sql result"];
        $result['status'] = false;
        echo json_encode($result);
        exit;
    } else {
        try {
            $stmt->execute();
        } catch (\Throwable $th) {
            $result['msg'] = ["sql result"];
            $result['status'] = false;
            echo json_encode($result);
            exit;
        }
        $result = $stmt->get_result();
        // get result to array
        $result = ($result->fetch_all(MYSQLI_ASSOC));

        echo json_encode($result);
        exit;
    }
} else {
    $sql = "SELECT student_id, student_name, student_email, account_status FROM students where student_id=?";

    $conn = mysqli_connect("localhost", "root", "", "school");
    $stmt = mysqli_stmt_init($conn);

    if (!$stmt->prepare($sql)) {
        $result['msg'] = ["sql result"];
        $result['status'] = false;
        echo json_encode($result);
        exit;
    } else {
        try {
            $stmt->bind_param('i', $id);
            $stmt->execute();
        } catch (\Throwable $th) {
            $result['msg'] = ["sql result"];
            $result['status'] = false;
            echo json_encode($result);
            exit;
        }
        $res = $stmt->get_result();
        // get result to array
        $result['msg'] = ($res->fetch_all(MYSQLI_ASSOC));
        $result['status'] = true;
        echo json_encode($result['msg']);
    }
}
