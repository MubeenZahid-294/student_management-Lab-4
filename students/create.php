<?php
require_once __DIR__ . '/../config/db.php';

$name = $email = $phone = $address = $course = '';
$errors = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $course = trim($_POST['course'] ?? '');

    if (empty($name)) {
        $errors[] = "Name is required";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (!empty($email)) {
        $sql = "SELECT id FROM student_records WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $errors[] = "Email already exists";
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $sql = "INSERT INTO student_records (name, email, phone, address, course) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $email, $phone, $address, $course);

        if ($stmt->execute()) {
            $success_message = "Student added successfully!";
            $name = $email = $phone = $address = $course = '';
        } else {
            $errors[] = "Error adding student: " . $conn->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>🎓 Student Management System</h1>
        <p>Add new students to your system</p>
    </header>

    <div class="container">
        <nav>
            <a href="index.php">📋 View Students</a>
            <a href="create.php">➕ Add Student</a>
        </nav>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <strong>Errors:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <div class="card">
            <h2>➕ Add New Student</h2>
            
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" 
                           value="<?php echo htmlspecialchars($name); ?>" 
                           placeholder="Enter student's full name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($email); ?>" 
                           placeholder="Enter email address" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" 
                           value="<?php echo htmlspecialchars($phone); ?>" 
                           placeholder="Enter phone number">
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" 
                              placeholder="Enter address"><?php echo htmlspecialchars($address); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="course">Course</label>
                    <input type="text" id="course" name="course" 
                           value="<?php echo htmlspecialchars($course); ?>" 
                           placeholder="Enter course name">
                </div>

                <button type="submit" class="btn btn-success">➕ Add Student</button>
                <a href="index.php" class="btn btn-danger">Cancel</a>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Student Management System</p>
    </footer>
</body>
</html>