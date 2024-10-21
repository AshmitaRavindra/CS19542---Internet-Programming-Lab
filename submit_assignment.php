<?php
if (!isset($_SESSION['student_id'])) {
    echo "<script>alert('Please log in to submit assignments.'); window.location.href='your_login_page.php';</script>";
    exit(); // Stop executing the script if the student is not logged in
}
// Include the database connection file
include 'db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the assignment ID and student ID from the POST request
    $assignment_id = $_POST['assignment_id'];
    $student_id = $_POST['student_id'] ?? null; // Use null coalescing operator

    // Ensure student_id is provided
    if ($student_id === null) {
        echo "<script>alert('Student ID is not set.'); window.history.back();</script>";
        exit();
    }

    // Check if a file was uploaded
    if (isset($_FILES['assignment_file']) && $_FILES['assignment_file']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['assignment_file']['tmp_name'];
        $fileName = $_FILES['assignment_file']['name'];
        $fileSize = $_FILES['assignment_file']['size'];
        $fileType = $_FILES['assignment_file']['type'];
        
        // Specify the directory where the file will be uploaded
        $uploadFileDir = './uploads/'; // Make sure this directory exists and is writable
        $dest_path = $uploadFileDir . $fileName;

        // Move the file to the specified directory
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Prepare and execute the insert statement
            $stmt = $conn->prepare("INSERT INTO submissions (assignment_id, student_id, file_path) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $assignment_id, $student_id, $dest_path);

            if ($stmt->execute()) {
                // File uploaded and data stored successfully
                echo "<script>alert('Assignment submitted successfully!'); window.location.href='your_previous_page.php?course_id={$course_id}';</script>";
            } else {
                // Database insert failed
                echo "<script>alert('Failed to submit assignment. Please try again.'); window.history.back();</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Error moving the uploaded file.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No file uploaded or there was an upload error.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='your_previous_page.php';</script>";
}

$conn->close();
?>
