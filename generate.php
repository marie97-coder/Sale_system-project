<?php
// Include dompdf autoload file
require_once 'dompdf-master/autoload.inc.php';

use Dompdf\Dompdf;

// Include your database connection file
include 'dta.php';

// Prepare HTML content for the report
$html = '
<h2 style="text-align:center;">Product Report</h2>
<table border="1" cellpadding="6" cellspacing="0" width="100%" style="border-collapse: collapse;">
    <thead>
        <tr style="background-color:#f2f2f2;">
            <th>Product Name</th>
            <th>Price (TZS)</th>
            <th>Total Registered</th>
            <th>Total Sold</th>
            <th>Remaining Stock</th>
        </tr>
    </thead>
    <tbody>
';

$sql = "SELECT 
    p.name,
    p.price,
    p.quantity AS total_registered,
    IFNULL(SUM(s.quantity_sold), 0) AS total_sold,
    (p.quantity - IFNULL(SUM(s.quantity_sold), 0)) AS remaining_stock
FROM products p
LEFT JOIN sales s ON p.id = s.product_id
GROUP BY p.id, p.name, p.price, p.quantity
ORDER BY p.name";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $html .= '<tr>
            <td>'.htmlspecialchars($row['name']).'</td>
            <td>'.number_format($row['price'], 2).'</td>
            <td>'.intval($row['total_registered']).'</td>
            <td>'.intval($row['total_sold']).'</td>
            <td>'.intval($row['remaining_stock']).'</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="5" style="text-align:center;">No products found.</td></tr>';
}

$html .= '
    </tbody>
</table>
';

// Initialize dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the PDF
$dompdf->render();

// Stream PDF to browser for preview (Attachment=0 means open in browser)
$dompdf->stream("product_report.pdf", ["Attachment" => 0]);

exit;
?>
