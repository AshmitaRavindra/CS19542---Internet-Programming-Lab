<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor') {
    header("Location: login.php");
    exit();
}

$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;

// Fetch assignments related to the selected course
$sql = "SELECT * FROM assignments WHERE course_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$assignments = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Assignments</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2>Manage Assignments for Course ID: <?= $course_id ?></h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($assignment = $assignments->fetch_assoc()): ?>
            <tr>
                <td><?= $assignment['id'] ?></td>
                <td><?= htmlspecialchars($assignment['title']) ?></td>
                <td>
                    <a href="edit_assignments.php?assignment_id=<?= $assignment['id'] ?>" class="btn btn-warning">Edit</a>
                    <a href="delete_assignment.php?assignment_id=<?= $assignment['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this assignment?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="add_assignment.php?course_id=<?= $course_id ?>" class="btn btn-primary">Add Assignment</a>
    <a href="instructor_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body>
</html>
