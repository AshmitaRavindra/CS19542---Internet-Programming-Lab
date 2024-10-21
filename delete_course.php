<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['course_id'])) {
    $course_id = intval($_GET['course_id']);
    
    // Prepare and execute delete query
    $sql = "DELETE FROM courses WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $course_id);
    
    if ($stmt->execute()) {
        // Redirect to the dashboard with a success message
        header("Location: instructor_dashboard.php?message=Course deleted successfully.");
        exit();
    } else {
        // Handle error
        echo "Error deleting course: " . $stmt->error;
    }
}
?>
