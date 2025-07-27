<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'boss') {
    header("Location: login.php");
    exit();
}

include 'db.php';
include 'header.php';

// Determine report type from query string (default to daily)
$report_type = $_GET['type'] ?? 'daily';

// Initialize variables
$title = '';
$sql = '';
$params = [];
$types = '';

// Build SQL based on report type
switch ($report_type) {
    case 'weekly':
        $title = "Weekly Sales Report";
        $sql = "SELECT YEAR(sale_date) AS year, WEEK(sale_date) AS week, SUM(total_price) AS total_sales
                FROM sales
                GROUP BY year, week
                ORDER BY year DESC, week DESC";
        break;
    case 'monthly':
        $title = "Monthly Sales Report";
        $sql = "SELECT YEAR(sale_date) AS year, MONTH(sale_date) AS month, SUM(total_price) AS total_sales
                FROM sales
                GROUP BY year, month
                ORDER BY year DESC, month DESC";
        break;
    case 'yearly':
        $title = "Yearly Sales Report";
        $sql = "SELECT YEAR(sale_date) AS year, SUM(total_price) AS total_sales
                FROM sales
                GROUP BY year
                ORDER BY year DESC";
        break;
    case 'daily':
    default:
        $title = "Daily Sales Report";
        $sql = "SELECT DATE(sale_date) AS date, SUM(total_price) AS total_sales
                FROM sales
                GROUP BY date
                ORDER BY date DESC";
        break;
}

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-5">
  <div class="card shadow-lg p-4">
    <h3 class="mb-4 text-center"><?= $title ?></h3>

    <div class="mb-3 text-center">
      <a href="?type=daily" class="btn btn-outline-primary <?= $report_type == 'daily' ? 'active' : '' ?>">Daily</a>
      <a href="?type=weekly" class="btn btn-outline-primary <?= $report_type == 'weekly' ? 'active' : '' ?>">Weekly</a>
      <a href="?type=monthly" class="btn btn-outline-primary <?= $report_type == 'monthly' ? 'active' : '' ?>">Monthly</a>
      <a href="?type=yearly" class="btn btn-outline-primary <?= $report_type == 'yearly' ? 'active' : '' ?>">Yearly</a>
    </div>

    <table class="table table-striped table-bordered">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <?php if ($report_type == 'daily'): ?>
            <th>Date</th>
          <?php elseif ($report_type == 'weekly'): ?>
            <th>Year</th>
            <th>Week Number</th>
          <?php elseif ($report_type == 'monthly'): ?>
            <th>Year</th>
            <th>Month</th>
          <?php else: ?>
            <th>Year</th>
          <?php endif; ?>
          <th>Total Sales (TZS)</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows === 0): ?>
          <tr><td colspan="4" class="text-center">No sales data available.</td></tr>
        <?php else: ?>
          <?php $count = 1; ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $count++ ?></td>
              <?php if ($report_type == 'daily'): ?>
                <td><?= htmlspecialchars($row['date']) ?></td>
              <?php elseif ($report_type == 'weekly'): ?>
                <td><?= $row['year'] ?></td>
                <td><?= $row['week'] ?></td>
              <?php elseif ($report_type == 'monthly'): ?>
                <td><?= $row['year'] ?></td>
                <td><?= $row['month'] ?></td>
              <?php else: ?>
                <td><?= $row['year'] ?></td>
              <?php endif; ?>
              <td><?= number_format($row['total_sales'], 2) ?></td>
            </tr>
          <?php endwhile; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
