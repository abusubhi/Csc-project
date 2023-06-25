<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<!-- here we go  -->

<body style="background-color:#CCCCFF;">
    <strong id="error-msg" style="color:red"></strong>
    <!-- Bootstrap JS SweetAlert -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <h1 class="mb-5 mt-5 text-center">Admin Dashboard</h1>
    <!-- Student control buttons -->
    <div class="container" style="background-color:#CCCCFF;">
        <div class="justify-content-between">
            <button type="button" class="btn btn-primary me-2 mb-1" data-toggle="modal" data-target="#newStudentModal">
                New Student
            </button>
            <button type="button" class="btn btn-primary me-2 mb-1" data-toggle="modal" data-target="#newCourseModal">
                New Course
            </button>
            <button type="button" class="btn btn-primary me-2 mb-1" data-toggle="modal" data-target="#assignCourseModal"
                id="assign_button">
                Assign Course
            </button>
            <button type="button" class="btn btn-primary me-2 mb-1" data-toggle="modal" data-target="#setMarkModal"
                id="set_mark_button">
                Set Mark
            </button>
        </div>

        <!-- Main Table, display all students -->
        <table class="table table-striped table-bordered" id="student_Info">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Account Status</th>
                    <th>Actions</th>
                </tr>
            <tbody>
                <script>
                    function add_student() {
                        swal({
                            title: "Success  ",
                            text: "Student added successfully!",
                            icon: "success",
                            button: "Ok",
                        }).then(function () {
                            location.reload();
                        });
                    }

                    function edit_student(data) {
                        modal = document.getElementById("editModalBody");
                        id_input = document.getElementById("edit_id")
                        id_input.setAttribute("value", data['student_id']);
                        id_input.setAttribute("disabled", "true");

                        name_input = document.getElementById("edit_name");
                        name_input.setAttribute("value", data['student_name']);

                        email_input = document.getElementById("edit_email");
                        email_input.setAttribute("value", data['student_email']);

                        status_input = document.getElementById("edit_status");

                        // status_input is a select element with two options
                        if (data['account_status'] == 1) {
                            status_input.options[0].selected = true;
                        } else {
                            status_input.options[1].selected = true;
                        }
                    }

                    function delete_student(data) {
                        swal({
                            title: "Are you sure?",
                            text: "Once deleted, you will not be able to recover this student!",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                            .then((willDelete) => {
                                if (willDelete) {
                                    $.ajax({
                                        url: "./controllers/delete_student.php",
                                        method: "POST",
                                        data: {
                                            "st_id": data,
                                        }
                                    }).done(function (data) {
                                        console.log(data);

                                        swal("Student Removed!", {
                                            icon: "success",
                                        }).then(function (d) {
                                            location.reload();
                                        })
                                    });
                                }
                            });
                    }

                    $(document).ready(function () {
                        $.ajax({
                            url: "./controllers/get_students.php",
                            method: "POST",
                            data: {
                                "st_id": null,
                                "num": null,
                            }
                        }).done(function (data) {
                            data = JSON.parse(data);

                            for (let i = 0; i < data.length; i++) {
                                var table = document.getElementById("student_Info");
                                var row = table.insertRow(-1);
                                var cell1 = row.insertCell(0);
                                var cell2 = row.insertCell(1);
                                var cell3 = row.insertCell(2);
                                var cell4 = row.insertCell(3);
                                var Action_cell = row.insertCell(4);

                                cell1.innerHTML = data[i].student_id;
                                cell2.innerHTML = data[i].student_name;
                                cell3.innerHTML = data[i].student_email;
                                cell4.innerHTML = data[i].account_status;

                                edit_button = document.createElement("button");
                                edit_button.innerHTML = "Edit";
                                edit_button.setAttribute("class", "btn btn-success");
                                edit_button.setAttribute("id", data[i].student_id);
                                edit_button.setAttribute("data-toggle", "modal");
                                edit_button.setAttribute("data-target", "#editModel");
                                edit_button.addEventListener("click", function (e) {
                                    // cuz data is an array of objects, (it can be stringified and re-parsed)
                                    edit_student(data[i]);
                                });

                                Action_cell.appendChild(edit_button);
                                delete_button = document.createElement("button");
                                delete_button.innerHTML = "Delete";
                                delete_button.setAttribute("class", "btn btn-danger ms-2");
                                // delete_button.setAttribute("id", data[i].student_id);
                                delete_button.setAttribute("onclick", "delete_student(" + data[i].student_id + ")");

                                Action_cell.appendChild(delete_button);
                            }
                        })
                    });
                </script>
            </tbody>
    </div>
    <!-- Edit Student Modal -->
    <form method="post" id="edit">
        <div class="modal fade" id="editModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Student Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="editModalBody">
                            <label class="form-label">Student Id</label>
                            <input type="text" id="edit_id" class="form-control" disabled>

                            <label class="form-label">Student Name</label>
                            <input type="text" id="edit_name" class="form-control">

                            <label class="form-label">Student Email</label>
                            <input type="text" id="edit_email" class="form-control">

                            <label class="form-label">Account Status</label>
                            <select id="edit_status" class="form-control">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>

                            
  
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Save changes">
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Add Student Modal -->
    <form method="post" id="add_student">
        <div class="modal fade" id="newStudentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Student</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="editModalBody">
                            <label class="form-label">Student Id</label>
                            <input type="text" id="add_student_id" class="form-control" disabled value="Auto Set">

                            <label class="form-label">Student Name</label>
                            <input type="text" id="add_student_name" class="form-control">

                            <label class="form-label">Student Email</label>
                            <input type="text" id="add_student_email" class="form-control">

                            <label class="form-label">Account Status</label>
                            <select id="add_student_status" class="form-control">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <label class="form-label">Password</label>
                            <input type="password" id="add_student_password" class="form-control">
                            <label class="form-label">Password</label>
                            <input type="password" id="add_student_password_confirm" class="form-control">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Save changes">
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Add Course -->
    <form method="post" id="add_course">
        <div class="modal fade" id="newCourseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Course</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="editModalBody">
                            <label class="form-label">Course Id</label>
                            <input type="text" id="add_course_id" class="form-control" disabled value="Auto Set">

                            <label class="form-label">Course Name</label>
                            <input type="text" id="add_course_name" class="form-control">

                            <label class="form-label">Course Pass Mark</label>
                            <input type="text" id="add_course_pass_mark" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Save changes">
                    </div>
                        
                    

                </div>
            </div>
        </div>
    </form>
    <!-- Assign Student A Course -->
    <form method="post" id="assign_Course">
        <div class="modal fade" id="assignCourseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Assign Course To Student</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="editModalBody">
                            <label class="form-label">Courses</label>
                            <select id="assign_course" class="form-control">
                                <option value="0" default>None</option>
                                <script>
                                   
                                    $(assign_button).click(function () {
                                        $.ajax({
                                            url: "./controllers/get_courses.php",
                                            method: "POST",
                                            data: {
                                                "cr_id": null,
                                                "num": null,
                                            }
                                        }).done(function (data) {
                                            data = JSON.parse(data);
                                            for (let i = 0; i < data.length; i++) {
                                                var list = document.getElementById("assign_course");
                                                var option = document.createElement("option");
                                                option.innerHTML = data[i].course_name;
                                                option.setAttribute('value', JSON.stringify(data[i]));
                                                list.appendChild(option);
                                            }
                                        })
                                    });
                                </script>
                            </select>

                            <label class="form-label">Students</label>
                            <select id="assign_student" class="form-control">
                                <option value="0" default>None</option>
                                <script>
                                    $(document).ready(function () {
                                        $.ajax({
                                            url: "./controllers/get_students.php",
                                            method: "POST",
                                            data: {
                                                "st_id": null,
                                                "num": null,
                                            }
                                        }).done(function (data) {
                                            data = JSON.parse(data);
                                            for (let i = 0; i < data.length; i++) {
                                                var list = document.getElementById("assign_student");
                                                var option = document.createElement("option");
                                                option.innerHTML = data[i].student_name;
                                                option.setAttribute('value', JSON.stringify(data[i]));
                                                list.appendChild(option);
                                            }
                                        })
                                    });
                                </script>
                            </select>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Save changes">
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Set Mark For Student in a specific course -->
    <form method="post" id="set_mark">
        <div class="modal fade" id="setMarkModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Set Student Mark</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="editModalBody">

                            <label class="form-label">Students</label>
                            <select id="add_student_mark" class="form-control">
                                <option value="0" default>None</option>
                                <script>
                                    $('#set_mark_button').click(function () {
                                        var list = document.getElementById("add_student_mark");
                                        list.innerHTML = "";
                                        var op = document.createElement("option");
                                        op.innerHTML = "None";
                                        op.setAttribute('value', 0);
                                        list.appendChild(op);
                                        $.ajax({
                                            url: "./controllers/get_students.php",
                                            method: "POST",
                                            data: {
                                                "st_id": null,
                                                "num": null,
                                            }
                                        }).done(function (data) {
                                            data = JSON.parse(data);
                                            for (let i = 0; i < data.length; i++) {
                                                var option = document.createElement("option");
                                                option.innerHTML = data[i].student_name;
                                                option.setAttribute('value', data[i].student_id);
                                                list.appendChild(option);
                                            }
                                        })
                                    });
                                </script>
                            </select>

                            <label class="form-label">Student's Courses</label>
                            <select id="add_course_mark" class="form-control">
                                <option value="0" default>None</option>
                                <script>
                                    $('#add_student_mark').change(function () {
                                        var list = document.getElementById("add_course_mark");
                                        list.innerHTML = "";
                                        var st_id = $("#add_student_mark").val();
                                        $.ajax({
                                            url: "./controllers/get_student_course.php",
                                            method: "POST",
                                            data: {
                                                "st_id": st_id,
                                            }
                                        }).done(function (res) {
                                            res = JSON.parse(res);
                                            if (res['status'] == false) {
                                                var option = document.createElement("option");
                                                option.innerHTML = res['msg'];
                                                option.setAttribute('value', 0);
                                                list.appendChild(option);
                                                return;
                                            }
                                            data = res['msg'];

                                            for (let i = 0; i < data['course_name'].length; i++) {
                                                var option = document.createElement("option");
                                                var course_name = data['course_name'][i];
                                                var course_id = data['course_id'][i];
                                                option.innerHTML = course_name;

                                                option.setAttribute('value', course_id);
                                                list.appendChild(option);
                                            }
                                        })
                                    });
                                </script>
                            </select>
                            <label class="form-label">Mark</label>
                            <input type="number" id="add_mark" class="form-control" placeholder="Enter Mark">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Save changes">
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>
        // Edit student
        $("#edit").submit(function (e) {
            e.preventDefault();
            var email = $("#edit_email").val().toLowerCase();
            var username = $("#edit_name").val().toLowerCase();
            // validation
            if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                var error = "Please enter a valid email address";
                swal({
                    title: "Error!",
                    text: error,
                    icon: "error",
                    button: "Ok",
                })
                return;
            } else if (!username.match(/^[a-zA-Z0-9]*$/)) {
                var error = "Only letters and numbers allowed for the username";
                swal({
                    title: "Error!",
                    text: error,
                    icon: "error",
                    button: "Ok",
                })
                return;
            }

            $.ajax({
                url: "./controllers/edit_student.php",
                method: "POST",
                data: {
                    "id": $("#edit_id").val(),
                    "name": $("#edit_name").val(),
                    "email": $("#edit_email").val(),
                    "status": $("#edit_status").val(),
                }
            }).done(function (data) {
                data = JSON.parse(data);
                if (data['status'] == true) {
                    swal({
                        title: "Success  ",
                        text: "Student updated successfully!",
                        icon: "success",
                        button: "Ok",
                    }).then(function () {
                        location.reload();
                    });
                } else {
                    swal({
                        title: "Internal Server Error!",
                        text: "Student NOT updated!",
                        icon: "error",
                        button: "Ok",
                    });
                }
            })
        })
        // Add student
        $('#add_student').submit(function (e) {
            e.preventDefault();
            password = $("#add_student_password").val();
            password_confirm = $("#add_student_password_confirm").val();
            username = $("#add_student_name").val().toLowerCase();
            email = $("#add_student_email").val().toLowerCase();
            if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                var error = "Please enter a valid email address";
                swal({
                    title: "Error!",
                    text: error,
                    icon: "error",
                    button: "Ok",
                })
                return;
            } else if (!username.match(/^[a-zA-Z0-9]*$/)) {
                var error = "Only letters and numbers allowed for the username";
                swal({
                    title: "Error!",
                    text: error,
                    icon: "error",
                    button: "Ok",
                })
                return;
            } else if (password !== password_confirm) {
                var error = ("Passwords do not match");
                swal({
                    title: "Error!",
                    text: error,
                    icon: "error",
                    button: "Ok",
                })
                return;
            } else if (!password.match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/)) {
                var error = "Password must be at least 8 characters long and contain at least one number, one uppercase letter, one lowercase letter, and one special character";
                swal({
                    title: "Error!",
                    text: error,
                    icon: "error",
                    button: "Ok",
                })
                return;
            }

            $.ajax({
                url: "./controllers/signup_check.php",
                method: "POST",
                data: {
                    "username": $("#add_student_name").val(),
                    "email": $("#add_student_email").val(),
                    "status": $("#add_student_status").val(),
                    "password": $("#add_student_password").val(),
                    "password_confirm": $("#add_student_password").val(),
                }
            }).done(function (data) {
                console.log(data)
                data = JSON.parse(data);
                if (data['status'] == true) {
                    swal({
                        title: "Success  ",
                        text: "Student added successfully!",
                        icon: "success",
                        button: "Ok",
                    }).then(function () {
                        location.reload();
                    });
                } else {
                    swal({
                        title: "Internal Server Error!",
                        text: "Student NOT added!",
                        icon: "error",
                        button: "Ok",
                    });
                }
            });
        });
        // Add course
        $('#add_course').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "./controllers/add_course.php",
                method: "POST",
                data: {
                    "name": $("#add_course_name").val(),
                    "pass_mark": $("#add_course_pass_mark").val(),
                }
            }).done(function (data) {
                data = JSON.parse(data);
                if (data['status'] == true) {
                    swal({
                        title: "Success  ",
                        text: "Course added successfully!",
                        icon: "success",
                        button: "Ok",
                    }).then(function () {
                        location.reload();
                    });
                } else {
                    swal({
                        title: "Internal Server Error!",
                        text: "Course NOT added!",
                        icon: "error",
                        button: "Ok",
                    });
                }
            });
        });
        // Assign Course
        $('#assign_Course').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "./controllers/assign_course.php",
                method: "POST",
                data: {
                    "course": JSON.parse($("#assign_course").val())['course_id'],
                    "student": JSON.parse($("#assign_student").val())['student_id']
                }
            }).done(function (data) {
                data = JSON.parse(data);
                if (data['status'] == true) {
                    swal({
                        title: "Success  ",
                        text: "Course assigned successfully!",
                        icon: "success",
                        button: "Ok",
                    })
                } else {
                    swal({
                        title: "Error!",
                        text: data['msg'],
                        icon: "error",
                        button: "Ok",
                    });
                }
            });
        });
    </script>
    <!-- Set mark -->
    <script>
        $('#set_mark').submit(function (e) {
            e.preventDefault();

            var mark = $("#add_mark").val();
            if (mark < 0 || mark > 100) {
                swal({
                    title: "Error!",
                    text: "Mark must be between 0 and 100!",
                    icon: "error",
                    button: "Ok",
                });
                return;
            }

            $.ajax({
                url: "./controllers/set_mark.php",
                method: "POST",
                data: {
                    "st_id": $("#add_student_mark").val(),
                    "course_id": $("#add_course_mark").val(),
                    "mark": mark,
                }
            }).done(function (data) {

                data = JSON.parse(data);
                if (data['status'] == true) {
                    swal({
                        title: "Success  ",
                        text: "Mark set successfully!",
                        icon: "success",
                        button: "Ok",
                    })
                } else {
                    swal({
                        title: "Error!",
                        text: data['msg'],
                        icon: "error",
                        button: "Ok",
                    });
                }
            });
        });
    </script>
</body>

</html>