<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sales Management System</title>
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  
  <!-- FontAwesome for icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <!-- Links for manager/admin: Add Product, View/Edit Products, View Sales, Reports -->
  <!-- Logout link always shown -->
</nav>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <i class="fas fa-store"></i> Sales Management
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['username'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">
              <i class="fas fa-sign-out-alt"></i> Logout
            </a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="login.php">Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid p-0">
<!-- Page content starts here -->

