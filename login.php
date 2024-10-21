<?php
include 'db.php'; // Include your database connection
session_start(); // Start the session at the top

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if user exists and password is valid
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Set student ID specifically for student role
        if ($user['role'] == 'student') {
            $_SESSION['student_id'] = $user['id']; // Set the student ID in session
            header("Location: user_dashboard.php"); // Redirect to student dashboard
        } elseif ($user['role'] == 'instructor') {
            header("Location: instructor_dashboard.php"); // Redirect to instructor dashboard
        } elseif ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php"); // Redirect to admin dashboard
        }
        exit();
    } else {
        echo "Invalid email or password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        head, body {
            margin: 0;
            height: 100%;
            font-family: Arial, Helvetica, sans-serif;
        }
        .video-background {
            position: fixed;
            z-index: -1;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
        }
        .form-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.5);
            max-width: 400px;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: white;
        }
        label {
            color: white;
        }
    </style>
    <title>User Login</title>
</head>
<body>
    <video autoplay muted loop class="video-background">
        <source src="/e-learning_management_system/video.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="container mt-5">
        <h2 class="text-white">LOGIN</h2>
        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form> 
    </div>
</body>
</html>
