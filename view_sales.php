<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'dta.php';
include 'header.php';
include 'navbar.php';
?>

<div class="container mt-5">
  <div class="card shadow p-4">
    <h3 class="text-center mb-4">📊 View Sales</h3>
    <table class="table table-bordered">
      <thead class="table-info">
        <tr>
          <th>#</th>
          <th>Product Name</th>
          <th>Quantity Sold</th>
          <th>Total Price (TZS)</th>
          <th>Sale Date</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query = "SELECT s.id, p.name, s.quantity_sold, (p.price * s.quantity_sold) AS total_price, s.sale_date 
                  FROM sales s
                  JOIN products p ON s.product_id = p.id
                  ORDER BY s.sale_date asc";
        $result = $conn->query($query);
        $count = 1;

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $count++ . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . intval($row['quantity_sold']) . "</td>";
            echo "<td>" . number_format($row['total_price'], 2) . "</td>";
            echo "<td>" . $row['sale_date'] . "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='5' class='text-center'>No sales found.</td></tr>";
        }
        ?>
      </tbody>
    </table>
    <?php
// === DAILY TOTAL ===
$daily = $conn->query("
  SELECT SUM(p.price * s.quantity_sold) AS total 
  FROM sales s 
  JOIN products p ON s.product_id = p.id 
  WHERE DATE(s.sale_date) = CURDATE()
")->fetch_assoc();

// === WEEKLY TOTAL ===
$weekly = $conn->query("
  SELECT SUM(p.price * s.quantity_sold) AS total 
  FROM sales s 
  JOIN products p ON s.product_id = p.id 
  WHERE WEEK(s.sale_date, 1) = WEEK(CURDATE(), 1) 
    AND YEAR(s.sale_date) = YEAR(CURDATE())
")->fetch_assoc();

// === MONTHLY TOTAL ===
$monthly = $conn->query("
  SELECT SUM(p.price * s.quantity_sold) AS total 
  FROM sales s 
  JOIN products p ON s.product_id = p.id 
  WHERE MONTH(s.sale_date) = MONTH(CURDATE()) 
    AND YEAR(s.sale_date) = YEAR(CURDATE())
")->fetch_assoc();

// === OVERALL TOTAL ===
$overall = $conn->query("
  SELECT SUM(p.price * s.quantity_sold) AS total 
  FROM sales s 
  JOIN products p ON s.product_id = p.id
")->fetch_assoc();
?>

<div class="mt-5">
  <h5 class="text-center">💰 Summary of Sales Totals</h5>
  <table class="table table-bordered text-center">
    <thead class="table-dark text-light">
      <tr>
        <th>Today</th>
        <th>This Week</th>
        <th>This Month</th>
        <th>All Time</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><strong><?= number_format($daily['total'] ?? 0, 2) ?> TZS</strong></td>
        <td><strong><?= number_format($weekly['total'] ?? 0, 2) ?> TZS</strong></td>
        <td><strong><?= number_format($monthly['total'] ?? 0, 2) ?> TZS</strong></td>
        <td><strong><?= number_format($overall['total'] ?? 0, 2) ?> TZS</strong></td>
      </tr>
    </tbody>
  </table>
</div>

  </div>
</div>
