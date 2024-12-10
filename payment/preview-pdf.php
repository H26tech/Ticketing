<?php
ob_start(); // Start output buffering
require '../vendor/autoload.php'; // Adjust this path
include '../include/connection/connection.php'; // Include your database connection

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="payments_data.pdf"'); // Change 'D' to 'I' for inline display
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');
header('Expires: 0');

// Create new PDF document
$pdf = new \TCPDF();

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Payments Data');
$pdf->SetSubject('Payment Records');
$pdf->SetKeywords('TCPDF, PDF, example, payment');

// Add a page
$pdf->AddPage();

// Fetch data from the database
$query = "SELECT * FROM payments"; // Adjust your query as needed
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Start building the HTML table
    $html = '<h1>Payments Data</h1>';
    $html .= '<table border="1" cellpadding="5">';
    $html .= '<thead>';
    $html .= '<tr>
                <th>Payment ID</th>
                <th>Full Name</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Status</th>
            </tr>';
    $html .= '</thead>';
    $html .= '<tbody>';

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($row['id']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['full_name']) . '</td>';
        $html .= '<td>Rp ' . htmlspecialchars($row['amount']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['created_at']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['status']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';
} else {
    $html = '<h2>No payment records found.</h2>';
}

// Print the HTML content
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// Output PDF document
$pdf->Output('payments_data.pdf', 'I'); // Output inline to display in browser
ob_end_flush(); // Flush the output buffer
exit();
