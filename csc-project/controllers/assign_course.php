<?php
$student = $_POST['student'];
$course = $_POST['course'];
$mark = 0;
$sql = "INSERT INTO student_course (student_id, course_id, mark) VALUES (?,?,?)";
$conn = mysqli_connect("localhost", "root", "", "school");
$stmt = mysqli_stmt_init($conn);

if (!$stmt->prepare($sql)) {
    $result['msg'] = "sql preparation error";
    $result['status'] = false;
    echo json_encode($result);
    exit;
} else {
    $stmt->bind_param('iii', $student, $course, $mark);
    try {
        $stmt->execute();
    } catch (\Throwable $th) {
        if ($th->getCode() == 1062) {
            $result['msg'] = "Student already assigned to this course";
            $result['status'] = false;
            echo json_encode($result);
            exit;
        } else {
            $result['msg'] = $th->getMessage();
            $result['status'] = false;
            echo json_encode($result);
            exit;
        }
    }
    $result['status'] = true;
    echo json_encode($result);
}
