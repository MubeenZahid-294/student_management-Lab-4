<?php
require_once __DIR__ . '/../config/db.php';

$id = $_GET['id'] ?? 0;
$success_message = '';
$error_message = '';

if ($id > 0) {
    $sql = "DELETE FROM student_records WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $success_message = "Student deleted successfully!";
    } else {
        $error_message = "Error deleting student: " . $conn->error;
    }
    
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>🎓 Student Management System</h1>
        <p>Delete a student</p>
    </header>

    <div class="container">
        <nav>
            <a href="index.php">📋 View Students</a>
            <a href="create.php">➕ Add Student</a>
        </nav>

        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
                <br><br>
                <a href="index.php" class="btn btn-primary">Back to List</a>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
                <br><br>
                <a href="index.php" class="btn btn-danger">Back to List</a>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Student Management System</p>
    </footer>
</body>
</html>