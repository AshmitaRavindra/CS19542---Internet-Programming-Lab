<?php
session_start();
include 'db.php';

// Check if the user is logged in and has the instructor role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor') {
    header("Location: login.php");
    exit();
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $due_date = trim($_POST['due_date']);
    $course_id = intval(trim($_POST['course_id'])); // Ensure course_id is an integer

    // Check for empty fields
    if (empty($title) || empty($description) || empty($due_date) || empty($course_id)) {
        $error_message = "All fields are required.";
    } else {
        // Prepare the SQL statement
        $sql = "INSERT INTO assignments (title, description, due_date, course_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("sssi", $title, $description, $due_date, $course_id);

            // Execute the statement
            if ($stmt->execute()) {
                // Redirect with a success message
                header("Location: instructor_dashboard.php?success=Assignment added successfully");
                exit();
            } else {
                // Display error message for execution failure
                $error_message = "Error executing statement: " . $stmt->error;
            }
            $stmt->close();
        } else {
            // Display error message for statement preparation failure
            $error_message = "Error preparing statement: " . $conn->error;
        }
    }
}

// Fetch courses to populate the dropdown
$courses_sql = "SELECT id, title FROM courses WHERE instructor_id = ?";
$courses_stmt = $conn->prepare($courses_sql);
$courses_stmt->bind_param("i", $_SESSION['user_id']);
$courses_stmt->execute();
$courses_result = $courses_stmt->get_result();
$courses_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Assignment</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Add New Assignment</h1>
        
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="title">Assignment Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="datetime-local" class="form-control" id="due_date" name="due_date" required>
            </div>
            <div class="form-group">
                <label for="course_id">Select Course</label>
                <select class="form-control" id="course_id" name="course_id" required>
                    <option value="">Select a course</option>
                    <?php while ($course = $courses_result->fetch_assoc()): ?>
                        <option value="<?php echo $course['id']; ?>"><?php echo $course['title']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Assignment</button>
            <a href="instructor_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </form>
    </div>
</body>
</html>
