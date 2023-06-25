<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <br>
    <div class="container">
        <table class="table table-bordered" id="student_Info">
            <thead>
                <tr>
                    <th colspan="2" class="text-center">
                        <h3>Student Info</h3>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Student Name</strong></td>
                    <td><strong>Student Email</strong></td>
                </tr>
                <tr>
                    <td><?php echo $_SESSION['username'] ?></td>
                    <td><?php echo $_SESSION['email'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <hr class="my-4">
    
    <div class="container">
        <table class="table table-bordered" id="course_info">
            <thead>
                <tr>
                    <th colspan="3" class="text-center">
                        <h3>Student Courses</h3>
                    </th>
                </tr>
                <tr>
                    <th>Course Name</th>
                    <th>Mark to Pass</th>
                    <th>Obtained Mark</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            var id = <?php echo $_SESSION['user_id'] ?>;

            console.log("ready");

            $.ajax({
                url: "./controllers/get_student_course.php",
                method: "POST",
                data: {
                    st_id: id,
                }
            }).done(function(data) {
                console.log(data);
                var data = JSON.parse(data);
                var marks = data.msg.marks;
                var pass_mark = data.msg.pass_mark;
                var course_name = data.msg.course_name;
                var table = document.getElementById("course_info");

                for (let i = 0; i < marks.length; i++) {
                    var row = table.insertRow(-1);
                    var cell1 = row.insertCell(0);
                    var cell2 = row.insertCell(1);
                    var cell3 = row.insertCell(2);

                    cell1.innerHTML = course_name[i];
                    cell2.innerHTML = pass_mark[i];
                    cell3.innerHTML = marks[i];

                    if (marks[i] >= pass_mark[i]) {
                        cell3.style.color = "green";
                    } else {
                        cell3.style.color = "red";
                    }
                }
            });
        });
    </script>
</body>

</html>