<?php
include 'dta.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);

    if ($name && $price > 0 && $quantity >= 0) {
        $stmt = $conn->prepare("INSERT INTO products (name, price, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $name, $price, $quantity);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: add_product.php?success=1");
        } else {
            header("Location: add_product.php?error=1");
        }
    } else {
        header("Location: add_product.php?error=1");
    }
}
?>
