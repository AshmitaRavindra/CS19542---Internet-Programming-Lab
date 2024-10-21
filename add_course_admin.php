<?php
session_start();
include 'db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location:login.php");
    exit();
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $instructor_id = $_POST['instructor_id'];  // Get the instructor ID from the form

    // Prepare the query to insert the course with the instructor ID
    $stmt = $conn->prepare("INSERT INTO courses (title, description, instructor_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $description, $instructor_id);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error; // Output any error
    }

    $stmt->close();
}

// Fetch all instructors to display in the form
$instructors = $conn->query("SELECT id, username FROM users WHERE role = 'instructor'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Add Course</title>
</head>
<body>
<div class="container">
    <h1 class="my-4">Add Course</h1>
    <form method="POST">
        <div class="form-group">
            <label for="title">Course Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Course Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="instructor_id">Instructor</label>
            <select name="instructor_id" class="form-control" required>
                <option value="">Select an Instructor</option>
                <?php while ($instructor = $instructors->fetch_assoc()): ?>
                    <option value="<?= $instructor['id'] ?>"><?= $instructor['username'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Course</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
