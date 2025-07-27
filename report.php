<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}
include 'dta.php';
include 'header.php';
include 'navbar.php';
?>
<style>
@media print {
  nav, .btn, .navbar, #searchInput, #printBtn {
    display: none !important;
  }
  body {
    margin: 20px;
  }
  table {
    width: 100%;
    border-collapse: collapse;
  }
  th, td {
    border: 1px solid #000 !important;
    padding: 8px;
  }
}
</style>

<script>
function printReport() {
  const originalContent = document.body.innerHTML;
  const reportContent = document.getElementById('reportContent').innerHTML;
  document.body.innerHTML = reportContent;
  window.print();
  document.body.innerHTML = originalContent;
  attachSearchListener();  // reattach listener after restoring content
}

function attachSearchListener() {
  const searchInput = document.getElementById("searchInput");
  if (!searchInput) return;
  const rows = document.querySelectorAll("#productTableBody tr");
  searchInput.addEventListener("keyup", function () {
    const input = this.value.toLowerCase();
    rows.forEach(row => {
      const productName = row.cells[0].textContent.toLowerCase();
      row.style.display = productName.includes(input) ? "" : "none";
    });
  });
}

document.addEventListener("DOMContentLoaded", () => {
  attachSearchListener();
});
</script>

<div class="container mt-5">
  <div class="card shadow p-4" id="reportContent">

    <div class="d-flex justify-content-end mb-3">
      <button onclick="printReport()" class="btn btn-primary" id="printBtn">🖨️ Print Report</button>
    </div>

    <h3 class="mb-4 text-center">📝 Product Activity Report</h3>

    <!-- Search Input -->
    <div class="mb-3">
      <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search product name...">
    </div>

    <!-- Current Products -->
    <h5>📦 Current Products in Stock</h5>
    <table class="table table-bordered">
      <thead class="table-success">
        <tr>
          <th>Name</th>
          <th>Price (TZS)</th>
          <th>Total Registered</th>
          <th>Total Sold</th>
          <th>Remaining Stock</th>
        </tr>
      </thead>
      <tbody id="productTableBody">
        <?php
        $query = "SELECT 
            p.id,
            p.name,
            p.price,
            p.quantity AS total_registered,
            IFNULL(SUM(s.quantity_sold), 0) AS total_sold,
            (p.quantity - IFNULL(SUM(s.quantity_sold), 0)) AS remaining_stock
          FROM products p
          LEFT JOIN sales s ON p.id = s.product_id
          GROUP BY p.id, p.name, p.price, p.quantity
          ORDER BY p.name";

        $res = $conn->query($query);
        if ($res->num_rows > 0) {
          while ($row = $res->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . number_format($row['price'], 2) . "</td>";
            echo "<td>" . intval($row['total_registered']) . "</td>";
            echo "<td>" . intval($row['total_sold']) . "</td>";
            echo "<td>" . intval($row['remaining_stock']) . "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='5' class='text-center'>No products found.</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <!-- Price Updates -->
    <h5 class="mt-5">🔄 Price Update History</h5>
    <table class="table table-bordered">
      <thead class="table-warning">
        <tr>
          <th>Product</th>
          <th>Old Price</th>
          <th>New Price</th>
          <th>Updated At</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $updates = $conn->query("SELECT * FROM price_updates ORDER BY updated_at DESC");
        if ($updates->num_rows > 0) {
          while ($row = $updates->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
            echo "<td>" . number_format($row['old_price'], 2) . "</td>";
            echo "<td>" . number_format($row['new_price'], 2) . "</td>";
            echo "<td>" . $row['updated_at'] . "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='4' class='text-center'>No price updates found.</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <!-- Deleted Products -->
    <h5 class="mt-5">🗑️ Deleted Products</h5>
    <table class="table table-bordered">
      <thead class="table-danger">
        <tr>
          <th>Product Name</th>
          <th>Deleted At</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $deletes = $conn->query("SELECT * FROM deleted_products ORDER BY deleted_at DESC");
        if ($deletes->num_rows > 0) {
          while ($row = $deletes->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . $row['deleted_at'] . "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='2' class='text-center'>No deleted products found.</td></tr>";
        }
        ?>
      </tbody>
    </table>

  </div>
</div>

<script>
function printReport() {
  // Save original body
  const originalContent = document.body.innerHTML;
  // Get report div content only
  const reportContent = document.getElementById('reportContent').innerHTML;
  // Replace body content with report content
  document.body.innerHTML = reportContent;
  // Print window
  window.print();
  // Restore original content
  document.body.innerHTML = originalContent;
  // Reattach event listeners after restoring content
  attachSearchListener();
}

// Search filter for products table only
function attachSearchListener() {
  const searchInput = document.getElementById("searchInput");
  const rows = document.querySelectorAll("#productTableBody tr");
  searchInput.addEventListener("keyup", function () {
    const input = this.value.toLowerCase();
    rows.forEach(row => {
      const productName = row.cells[0].textContent.toLowerCase();
      row.style.display = productName.includes(input) ? "" : "none";
    });
  });
}

// Attach search listener when DOM loaded
document.addEventListener("DOMContentLoaded", () => {
  attachSearchListener();
});
</script>

<?php include 'footer.php'; ?>
