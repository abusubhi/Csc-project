<?php

$resp = array();
// takes student (student_id) and returns => (course_name, pass_mark, mark, course_id{from student_course table}})


// if get request is sent to display error message
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $resp['msg'] = ["forbidden method"];
    $resp['status'] = false;
    echo (json_encode($resp['msg']));
    exit;
}

$st_id = $_POST['st_id'];

$sql = "SELECT * FROM student_course WHERE student_id=?";
$conn = mysqli_connect("localhost", "root", "", "school");
$stmt = mysqli_stmt_init($conn);

if (!$stmt->prepare($sql)) {
    $resp['msg'] = ["sql error"];
    $resp['status'] = false;
    echo json_encode($resp);
    exit;
} else {
    $stmt->bind_param("i", $st_id);

    try {
        $stmt->execute();
    } catch (\Throwable $th) {
        $resp['msg'] = ["sql error"];
        $resp['status'] = false;
        echo json_encode($resp);
        exit;
    }
    $result = $stmt->get_result();
    // get result to array
    $result = ($result->fetch_all(MYSQLI_ASSOC));
    $course_id = array();
    $marks = array();
    foreach ($result as $key => $value) {
        $course_id[] = $value['course_id'];
        $marks[] = $value['mark'];
    }
    if (count($course_id) == 0) {
        $resp['msg'] = ["Not Enrolled In Courses"];
        $resp['status'] = false;
        echo json_encode($resp);
        exit;
    }
    // send another query to get student_course table and get mark to pass
    $sql = "SELECT course_name, pass_mark, course_id FROM courses WHERE course_id IN (" . implode(",", $course_id) . ")";

    $stmt = mysqli_stmt_init($conn);
    if (!$stmt->prepare($sql)) {
        $resp['msg'] = ["sql error1"];
        // $resp['msg'] = [$stmt->error]; // for debugging
        $resp['status'] = false;
        echo json_encode($resp);
        exit;
    } else {
        try {
            $stmt->execute();
        } catch (\Throwable $th) {
            $resp['msg'] = [$th->getMessage()];
            $resp['status'] = false;
            echo json_encode($resp);
            exit;
        }
    }
    $result = $stmt->get_result();
    $result = ($result->fetch_all(MYSQLI_ASSOC));
    $marks_to_pass = array();
    $course_names = array();
    foreach ($result as $key => $value) {
        $marks_to_pass[] = $value['pass_mark'];
        $course_names[] = $value['course_name'];
    }
    $resp['msg'] = ["marks" => $marks, "pass_mark" => $marks_to_pass, "course_name" => $course_names, "course_id" => $course_id];

    // returns => (course_name, pass_mark, student mark, course_id{from student_course table})
    $resp['status'] = true;
    echo json_encode($resp);
    exit;
}
