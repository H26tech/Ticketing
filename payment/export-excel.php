<?php
require '../vendor/autoload.php'; // Ensure this path matches your project

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Create a new Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Payments');

// Set header columns
$headers = ['Order Id', 'Full Name', 'Status', 'Selected Seats', 'Payment Method', 'Total', 'Date'];
$sheet->fromArray($headers, null, 'A1');

// Fetch data from the database
include '../include/connection/connection.php'; 
$query = "SELECT id, full_name, status, selected_seats, payment_method, amount, created_at FROM payments";
$result = $conn->query($query);

// Add rows to the spreadsheet
$rowNum = 2;
while ($row = $result->fetch_assoc()) {
    $sheet->fromArray(array_values($row), null, 'A' . $rowNum++);
}

// Download the file as .xlsx
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="payments.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
