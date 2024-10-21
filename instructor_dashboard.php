<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM courses WHERE instructor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$courses = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .navbar {
            width: 250px;
            position: fixed;
            height: 100%;
            background-color: #17a2b8;
            color: white;
            padding: 20px;
            box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-size: 1.5rem;
            color: white;
            font-weight: bold;
            text-align: center;
            display: block;
            margin-bottom: 1rem;
        }
        .navbar-nav {
            list-style: none;
            padding: 0;
        }
        .navbar-nav li {
            margin: 15px 0;
        }
        .navbar-nav li a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
        }
        .navbar-nav li a:hover {
            text-decoration: underline;
        }
        .content {
            margin-left: 270px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            height: 100vh;
        }
        h1, h2 {
            text-align: center;
        }
        table {
            background-color: white;
            width: 100%;
            border-radius: 8px;
        }
        th, td {
            text-align: center;
            padding: 12px;
        }
        .btn-info, .btn-warning, .btn-danger {
            margin-right: 10px;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <a class="navbar-brand" href="#">E-Learning System</a>
        <ul class="navbar-nav">
            <!-- Removed the Add Course link -->
            <li><a href="add_assignment.php">Add Assignments</a></li>
        </ul>
        <ul class="navbar-nav mt-auto">
            <li><a href="logout_instructors.php">Logout</a></li>
        </ul>
    </div>

    <div class="content">
        <h1>Welcome to the Instructor Dashboard</h1>
        <h2>Your Courses</h2>
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($course = $courses->fetch_assoc()): ?>
                <tr>
                    <td><?= $course['id'] ?></td>
                    <td><?= htmlspecialchars($course['title']) ?></td>
                    <td><?= htmlspecialchars($course['description']) ?></td>
                    <td>
                        <a href="manage_assignments.php?course_id=<?= $course['id'] ?>" class="btn btn-info">Manage Assignments</a>
                        <a href="edit_course.php?course_id=<?= $course['id'] ?>" class="btn btn-warning">Edit</a>
                        <a href="delete_course.php?course_id=<?= $course['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
