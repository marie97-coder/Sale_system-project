<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

include 'dta.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Check if product has related sales
    $stmt = $conn->prepare("SELECT COUNT(*) AS sale_count FROM sales WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['sale_count'] > 0) {
        echo "<div style='padding:20px; font-family:sans-serif;'>
                <h3 style='color:red;'>Cannot delete product!</h3>
                <p>This product has existing sales and cannot be deleted.</p>
                <a href='manage_products.php'>Go Back to Products</a>
              </div>";
        exit();
    }

    // Step 1: Get product info before deletion
    $stmt = $conn->prepare("SELECT name, price, quantity FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if ($product) {
        // Step 2: Insert into deleted_products table
        $stmt = $conn->prepare("INSERT INTO deleted_products (product_id, name, price, quantity) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isdi", $product_id, $product['name'], $product['price'], $product['quantity']);
        $stmt->execute();
    }

    // Step 3: Delete from products
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    header("Location: manage_products.php");
    exit();
} else {
    header("Location: manage_products.php");
    exit();
}
?>
