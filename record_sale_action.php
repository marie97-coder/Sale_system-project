<?php
session_start();
include 'dta.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = intval($_POST['product_id']);
    $quantity_sold = intval($_POST['quantity_sold']);

    // 1. Check product availability
    $stmt = $conn->prepare("SELECT price, quantity FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product && $product['quantity'] >= $quantity_sold) {
        // 2. Calculate total
        $price = $product['price'];
        $total_price = $price * $quantity_sold;

        // 3. Record the sale
        $insert = $conn->prepare("INSERT INTO sales (product_id, quantity_sold, total_price) VALUES (?, ?, ?)");
        $insert->bind_param("iid", $product_id, $quantity_sold, $total_price);
        $insert->execute();

        // 4. Reduce stock
        $update = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
        $update->bind_param("ii", $quantity_sold, $product_id);
        $update->execute();

        header("Location: record_sale.php?success=1");
    } else {
        header("Location: record_sale.php?error=1");
    }
}
?>
