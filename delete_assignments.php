<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor') {
    header("Location: login.php");
    exit();
}

$assignment_id = isset($_GET['assignment_id']) ? intval($_GET['assignment_id']) : 0;

// First, fetch the course_id to redirect later
$sql = "SELECT course_id FROM assignments WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $assignment_id);
$stmt->execute();
$assignment = $stmt->get_result()->fetch_assoc();
$course_id = $assignment['course_id'];

// Delete the assignment from the database
$delete_sql = "DELETE FROM assignments WHERE id = ?";
$delete_stmt = $conn->prepare($delete_sql);
$delete_stmt->bind_param("i", $assignment_id);

if ($delete_stmt->execute()) {
    header("Location: manage_assignments.php?course_id=" . $course_id);
    exit();
} else {
    echo "Error: " . $delete_stmt->error;
}

$delete_stmt->close();
?>
