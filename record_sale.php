<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'dta.php';
include 'header.php';
include 'navbar.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity_sold = $_POST['quantity_sold'];

    // Check if enough stock exists and get product name
    $check = $conn->prepare("SELECT name, quantity FROM products WHERE id = ?");
    $check->bind_param("i", $product_id);
    $check->execute();
    $result = $check->get_result();
    $product = $result->fetch_assoc();

    if ($product && $quantity_sold > 0 && $quantity_sold <= $product['quantity']) {
        // Record the sale
        $stmt = $conn->prepare("INSERT INTO sales (product_id, quantity_sold, sale_date) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $product_id, $quantity_sold);
        $stmt->execute();

        $success_message = "Sale recorded successfully!!.";
    } elseif ($product && $quantity_sold > $product['quantity']) {
        $available = $product['quantity'];
        $product_name = htmlspecialchars($product['name']);
        $error_message = " Quantity entered is greater than available stock. Only <strong>$available</strong> left for product <strong>\"$product_name\"</strong>.";
    } else {
        $error_message = " Invalid quantity entered.";
    }
}
?>

<div class="container mt-5">
  <div class="card shadow p-4">
    <h3 class="text-center mb-4"> Record Sale</h3>

    <?php if ($success_message): ?>
      <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="product_id" class="form-label">Select Product:</label>
        <select name="product_id" id="product_id" class="form-select" required>
          <option value="">-- Choose Product --</option>
          <?php
          $products = $conn->query("SELECT id, name FROM products ORDER BY name");
          while ($row = $products->fetch_assoc()) {
              echo "<option value='{$row['id']}'>" . htmlspecialchars($row['name']) . "</option>";
          }
          ?>
        </select>
      </div>

      <div class="mb-3">
        <label for="quantity_sold" class="form-label">Quantity Sold:</label>
        <input type="number" name="quantity_sold" class="form-control" required min="1">
        <?php if ($error_message): ?>
          <div class="text-danger mt-2"><?php echo $error_message; ?></div>
        <?php endif; ?>
      </div>

      <button type="submit" class="btn btn-primary w-100">Record Sale</button>
    </form>
  </div>
</div>
