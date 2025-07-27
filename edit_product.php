<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

include 'dta.php';

if (!isset($_GET['id'])) {
    die("Product ID is missing.");
}

$id = (int)$_GET['id'];

// Kama form imetumwa (POST) fanya update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $price = (float)$_POST["price"];

    // Pata old price
    $stmtOld = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $stmtOld->bind_param("i", $id);
    $stmtOld->execute();
    $oldProduct = $stmtOld->get_result()->fetch_assoc();
    $old_price = (float)$oldProduct['price'];

    // Update product
    $stmt = $conn->prepare("UPDATE products SET name=?, price=? WHERE id=?");
    $stmt->bind_param("sdi", $name, $price, $id);
    $stmt->execute();

    // Rekodi mabadiliko ya price ikiwa imebadilika
    if ($old_price !== $price) {
        $log = $conn->prepare("INSERT INTO price_updates (product_name, old_price, new_price) VALUES (?, ?, ?)");
        $log->bind_param("sdd", $name, $old_price, $price);
        $log->execute();
    }

    header("Location: manage_products.php?updated=1");
    exit();
}

// Onyesha product details form
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die("Product not found.");
}
?>

<?php include 'header.php'; 
include 'navbar.php';?>
<div class="container mt-5" style="max-width: 500px;">
  <div class="card p-4 shadow">
    <h3 class="mb-3 text-center">✏️ Edit Product</h3>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($product['name']) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Price (TZS)</label>
        <input type="number" name="price" class="form-control" step="0.01" required value="<?= $product['price'] ?>">
      </div>

      <button type="submit" class="btn btn-success w-100">Update Product</button>
    </form>
  </div>
</div>
