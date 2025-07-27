<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'employee') {
    header("Location: login.php");
    exit();
}
?>

<?php include 'header.php'; ?>
<div class="container mt-5">
  <div class="card p-4 shadow">
    <h4 class="mb-4 text-center">Employee Dashboard</h4>
    <a href="add_product.php" class="btn btn-primary mb-3">➕ Register New Product</a>
    <a href="record_sale.php" class="btn btn-success mb-3">💰 Record Sale</a>
  </div>
</div>
