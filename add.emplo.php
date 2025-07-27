<?php
session_start();

// Ensure only admin or manager can add new employees
if (!isset($_SESSION['username']) || ($_SESSION['role'] !== 'manager')) {
    header("Location: login.php");
    exit();
}

include 'db.php';
include 'header.php';
include 'navbar.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'employee'; // Always register as employee

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        $message = "New employee added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Employee</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

    <div class="card shadow p-5">
     <div class="d-grid gap-4 col-md-7 mx-auto">
    <h2 class="mb-3 text-center">Add Employee</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
        
            <label for="username"><strong>Employee Username</strong></label>
            <input type="text" name="username" class="form-control" required />
        </div>
        <div class="mb-3">
            
            <label for="password"><strong> Password</strong></label>
            <input type="password" name="password" class="form-control" required />
        </div>
        <button type="submit" class=" text-center btn btn-primary">Add Employee</button>
    </form>
     </div>
    </div>
</body>
</html>
