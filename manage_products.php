<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

include 'dta.php';
include 'header.php';
include 'navbar.php';

// Fetch products
$result = $conn->query("SELECT * FROM products ORDER BY id ASC");
?>

<div class="container mt-5">
  <div class="card shadow p-4">
    <h3 class="text-center mb-4">📦 Product List</h3>

    <!-- Search Input -->
    <div class="mb-3">
      <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search product name...">
    </div>

    <!-- Print Button -->
    <div class="mb-3 text-end">
      <button class="btn btn-primary" onclick="printReport()">🖨️ Print</button>
    </div>

    <!-- Report Table Container -->
    <div id="reportContent">
      <table id="productsTable" class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price (TZS)</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows === 0): ?>
            <tr><td colspan="4" class="text-center">No products found.</td></tr>
          <?php else: ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= number_format($row['price'], 2) ?></td>
                <td>
                  <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- JavaScript: Search Filter -->
<script>
document.getElementById("searchInput").addEventListener("keyup", function () {
  const input = this.value.toLowerCase();
  const rows = document.querySelectorAll("#productsTable tbody tr");

  rows.forEach(function (row) {
    const productName = row.cells[1].textContent.toLowerCase();
    row.style.display = productName.includes(input) ? "" : "none";
  });
});
</script>

<!-- JavaScript: Print Without Actions Column -->
<script>
function printReport() {
  const originalContent = document.body.innerHTML;
  const reportElement = document.getElementById("reportContent").cloneNode(true);

  // Remove 'Actions' column
  const ths = reportElement.querySelectorAll("th");
  ths.forEach((th, index) => {
    if (th.textContent.trim() === "Actions") {
      const rows = reportElement.querySelectorAll("tr");
      rows.forEach(row => {
        if (row.cells.length > index) {
          row.deleteCell(index);
        }
      });
    }
  });

  // Replace page content with report only
  document.body.innerHTML = reportElement.innerHTML;
  window.print();
  document.body.innerHTML = originalContent;
  window.location.reload();
}
</script>
