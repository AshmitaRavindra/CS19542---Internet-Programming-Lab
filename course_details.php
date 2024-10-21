<?php
session_start();
include 'db.php';

// Fetch all available courses from the database
$sql = "SELECT id, title, description FROM courses";
$result = $conn->query($sql);
?>

<div class="container mt-4">
    <h1 class="text-center">Available Courses</h1>
    <div class="row">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($course = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($course['description']) ?></p>
                            <a href="view_details.php?course_id=<?= htmlspecialchars($course['id']) ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-danger">No courses available at the moment.</p>
        <?php endif; ?>
    </div>
</div>
