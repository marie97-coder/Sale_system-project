<?php
session_start();
include 'dta.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $password_hashed = md5($password);

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($password_hashed === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } elseif ($user['role'] == 'manager') {
                header("Location: admin_dashboard.php");
            } elseif ($user['role'] == 'employee') {
                header("Location: employee_dashboard.php");
            } else {
                header("Location: login.php");
            }
            exit();
        } else {
            $error = "Wrong Password.";
        }
    } else {
        $error = "Invalid user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - Sales Management System</title>

  <!-- Bootstrap 5 CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
    body {
      
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .login-card {
      width: 100%;
      max-width: 400px;
      padding: 2rem;
      border-radius: 0.5rem;
      background-color: #fff;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
    }
    .login-title {
      color: #0d6efd;
      font-weight: 700;
      margin-bottom: 1.5rem;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="login-card">
    <h2 class="login-title">Login - Sales Management System</h2>

    <?php if (isset($error)) : ?>
      <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input
          type="text"
          class="form-control"
          id="username"
          name="username"
          required
          autofocus
          placeholder="Enter your username"
        />
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input
          type="password"
          class="form-control"
          id="password"
          name="password"
          required
          placeholder="Enter your password"
        />
      </div>

      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>

  <!-- Bootstrap 5 JS Bundle CDN (Optional, for interactive components) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
