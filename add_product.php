<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


include 'header.php';
include 'navbar.php';
?>

<div class="container mt-5">
  <div class="card shadow-lg p-4">
    <h3 class="text-center mb-4">➕ Add New Product</h3>

    <?php if (isset($_POST['success'])): ?>
      <div class="alert alert-success">✅ Product added successfully.</div>
    <?php elseif (isset($_GET['error'])): ?>
      <div class="alert alert-danger">❌ Failed to add product. Please try again.</div>
    <?php endif; ?>

    <form action="add_product_action.php" method="POST">
      <div class="mb-3">
        <label>Product Name</label>
        <input type="text" class="form-control" name="name" required>
      </div>

      <div class="mb-3">
        <label>Price per Unit</label>
        <input type="number" step="0.01" class="form-control" name="price" required>
      </div>

      <div class="mb-3">
        <label>Quantity</label>
        <input type="number" class="form-control" name="quantity" required>
      </div>

      <button class="btn btn-primary w-100" type="submit">Add Product</button>
    </form>
  </div>
</div>
