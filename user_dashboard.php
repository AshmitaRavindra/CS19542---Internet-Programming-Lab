<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ensure 'username' is set in session
if (!isset($_SESSION['username'])) {
    // Query the database to get the username using the user_id
    $user_id = $_SESSION['user_id'];
    $query = "SELECT username FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $row['username']; // Set the username in session
    } else {
        echo "Failed to retrieve user information.";
        exit();
    }
}

// Now that username is set, you can safely use it
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
        }
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar h4 {
            color: #fff;
        }
        .sidebar .nav-link {
            color: #fff;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
            color: #fff;
        }
        .content {
            margin-left: 270px;
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center">Welcome, <?= htmlspecialchars($username) ?>!</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#" id="viewCourses">Your Courses</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" id="editProfile">Edit Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="users_logout.php">Logout</a>
            </li>
        </ul>
    </div>

    <div class="content" id="mainContent">
        <h2 class="text-center">Welcome to your Dashboard</h2>
    </div>

    <script>
        $(document).ready(function() {
            // Load courses
            $('#viewCourses').click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'course_details.php', // Load courses dynamically
                    method: 'GET',
                    success: function(response) {
                        $('#mainContent').html(response); // Replace content with courses
                    },
                    error: function() {
                        $('#mainContent').html('<p class="text-danger">Failed to load courses.</p>');
                    }
                });
            });

            // Load edit profile
            $('#editProfile').click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'edit_profile.php', // Load edit profile dynamically
                    method: 'GET',
                    success: function(response) {
                        $('#mainContent').html(response); // Replace content with edit profile
                    },
                    error: function() {
                        $('#mainContent').html('<p class="text-danger">Failed to load profile edit form.</p>');
                    }
                });
            });
        });
    </script>
</body>
</html>
