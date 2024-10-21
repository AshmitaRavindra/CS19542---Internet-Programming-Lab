<?php
// Include the database connection file
include 'db.php';

// Get the course ID from the URL (use 'course_id' to get the assignments for a specific course)
$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : 0;

// Check if the course_id is valid
if ($course_id > 0) {
    // Fetch the assignments related to this course from the database
    $stmt = $conn->prepare("SELECT * FROM assignments WHERE course_id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    // If no course is selected, show an error message or redirect
    echo "<h2>Invalid course ID</h2>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Assignments for Course</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            margin-top: 50px;
            width: 60%;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            background-color: white;
        }
        .table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Assignments List</h2>

        <!-- Display the assignments for the selected course -->
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Due Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($assignment = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($assignment['id']) ?></td>
                            <td><?= htmlspecialchars($assignment['title']) ?></td>
                            <td><?= htmlspecialchars($assignment['description']) ?></td>
                            <td><?= htmlspecialchars($assignment['due_date']) ?></td>
                            <td>
                                <form action="submit_assignment.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="assignment_id" value="<?= htmlspecialchars($assignment['id']) ?>">
                                    <input type="hidden" name="course_id" value="<?= htmlspecialchars($course_id) ?>">
                                    <input type="file" name="assignment_file" required>
                                    <button type="submit" class="btn btn-primary">Submit Assignment</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No assignments found for this course.</p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
