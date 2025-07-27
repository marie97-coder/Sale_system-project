<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

include 'header.php';
?>

<div class="container mt-5">
  <div class="card shadow p-5">
    <h3 class="mb-4 text-center">Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h3>
    <p class="text-center text-success">✅ You are logged in as <strong>Manager (Admin)</strong></p>

    <div class="d-grid gap-3 col-md-6 mx-auto">
      <a href="add_employee.php" class="btn btn-danger">➕ Add New Employee</a>
      <a href="add_product.php" class="btn btn-primary">➕ Add New Product</a>
      <a href="manage_products.php" class="btn btn-info">📦 View/Edit/Delete Products</a>
      <a href="record_sale.php" class="btn btn-success">🛒 Record Sale</a>
      <a href="view_sales.php" class="btn btn-dark">📊 View Sales</a>
      <a href="report.php" class="btn btn-warning">📅 View Reports</a>

      <a href="logout.php" class="btn btn-outline-secondary">🔓 Logout</a>
    </div>
  </div>
</div>
