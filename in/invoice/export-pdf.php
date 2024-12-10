<?php
require_once '../vendor/autoload.php';
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

// Fetch full name from the query parameter
$fullName = isset($_GET['full_name']) ? $_GET['full_name'] : null;

if ($fullName) {
    include "../include/connection/connection.php";

    // Fetch user details
    $stmt = $conn->prepare("SELECT phone_number, email FROM users WHERE full_name = ?");
    $stmt->bind_param('s', $fullName);
    $stmt->execute();
    $userResult = $stmt->get_result();

    if ($userResult->num_rows > 0) {
        $userDetails = $userResult->fetch_assoc();
        $phoneNumber = $userDetails['phone_number'];
        $email = $userDetails['email'];
    } else {
        die('User not found');
    }

    // Fetch payment details
    $stmt = $conn->prepare("SELECT * FROM payments WHERE full_name = ?");
    $stmt->bind_param('s', $fullName);
    $stmt->execute();
    $paymentResult = $stmt->get_result();

    if ($paymentResult->num_rows > 0) {
        $paymentDetails = $paymentResult->fetch_assoc();
        $selectedSeats = explode(',', $paymentDetails['selected_seats']); // Get selected seats

        // Prepare to fetch seat prices
        $seatPrices = [];
        $totalAmount = 0;

        // Fetch prices for each selected seat
        foreach ($selectedSeats as $seat) {
            $rowLetter = substr($seat, 0, 1); 
            $seatNumber = substr($seat, 1); 

            $stmt = $conn->prepare("SELECT price FROM seats WHERE row_letter = ? AND seat_number = ?");
            $stmt->bind_param('ss', $rowLetter, $seatNumber);
            $stmt->execute();
            $priceResult = $stmt->get_result();

            if ($priceResult->num_rows > 0) {
                $priceDetails = $priceResult->fetch_assoc();
                $seatPrices[$seat] = $priceDetails['price'];
                $totalAmount += $priceDetails['price']; 
            }
        }

        // Fetch package details if selected
        $packageAmount = $paymentDetails['package'];
        $packageDescription = '';
        if ($packageAmount > 0) {
            switch ($packageAmount) {
                case 10000:
                    $packageDescription = "Paket A (Tambah Rp 10.000 - Biscuit, Merch)";
                    break;
                case 20000:
                    $packageDescription = "Paket B (Tambah Rp 20.000 - Minuman, Merch)";
                    break;
                case 30000:
                    $packageDescription = "Paket C (Tambah Rp 30.000 - Makanan, Minuman, Merch)";
                    break;
            }
            $seatPerUnit = $totalAmount / count($selectedSeats);
            $seatQty = count($selectedSeats);
            $subTotal = $totalAmount + $packageAmount;
            $totalTax = $subTotal * 0; // Assuming no tax for this example
            $grandTotal = $subTotal + $totalTax;
        }

        $orderDescription = "Bluvocation Film Fest Seats: " . implode(", ", $selectedSeats);
    } else {
        die('No payments found for this user');
    }
} else {
    die('Full name is required');
}

// Create a new mPDF instance
$mpdf = new \Mpdf\Mpdf();


// Load external CSS files
$cssFiles = [
    'C:/laragon/www/Ticketing Bluvocation/syndron-bootstrap5-admin-template-2022-08-08-06-06-10-utc/main-files/demo/vertical/assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css',
    'C:/laragon/www/Ticketing Bluvocation/syndron-bootstrap5-admin-template-2022-08-08-06-06-10-utc/main-files/demo/vertical/assets/plugins/simplebar/css/simplebar.css',
    'C:/laragon/www/Ticketing Bluvocation/syndron-bootstrap5-admin-template-2022-08-08-06-06-10-utc/main-files/demo/vertical/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css',
    'C:/laragon/www/Ticketing Bluvocation/syndron-bootstrap5-admin-template-2022-08-08-06-06-10-utc/main-files/demo/vertical/assets/plugins/metismenu/css/metisMenu.min.css',
    'C:/laragon/www/Ticketing Bluvocation/syndron-bootstrap5-admin-template-2022-08-08-06-06-10-utc/main-files/demo/vertical/assets/plugins/datatable/css/dataTables.bootstrap5.min.css',
    'C:/laragon/www/Ticketing Bluvocation/syndron-bootstrap5-admin-template-2022-08-08-06-06-10-utc/main-files/demo/vertical/assets/plugins/fancy-file-uploader/fancy_fileupload.css',
    'C:/laragon/www/Ticketing Bluvocation/syndron-bootstrap5-admin-template-2022-08-08-06-06-10-utc/main-files/demo/vertical/assets/plugins/input-tags/css/tagsinput.css',
    'C:/laragon/www/Ticketing Bluvocation/syndron-bootstrap5-admin-template-2022-08-08-06-06-10-utc/main-files/demo/vertical/assets/plugins/OwlCarousel/css/owl.carousel.min.css',
    'C:/laragon/www/Ticketing Bluvocation/syndron-bootstrap5-admin-template-2022-08-08-06-06-10-utc/main-files/demo/vertical/assets/css/pace.min.css',
    'C:/laragon/www/Ticketing Bluvocation/syndron-bootstrap5-admin-template-2022-08-08-06-06-10-utc/main-files/demo/vertical/assets/css/bootstrap.min.css',
    'C:/laragon/www/Ticketing Bluvocation/syndron-bootstrap5-admin-template-2022-08-08-06-06-10-utc/main-files/demo/vertical/assets/css/bootstrap-extended.css',
    'C:/laragon/www/Ticketing Bluvocation/syndron-bootstrap5-admin-template-2022-08-08-06-06-10-utc/main-files/demo/vertical/assets/css/app.css',
    'C:/laragon/www/Ticketing Bluvocation/syndron-bootstrap5-admin-template-2022-08-08-06-06-10-utc/main-files/demo/vertical/assets/css/icons.css',
    'C:/laragon/www/Ticketing Bluvocation/syndron-bootstrap5-admin-template-2022-08-08-06-06-10-utc/main-files/demo/vertical/assets/css/dark-theme.css',
    'C:/laragon/www/Ticketing Bluvocation/syndron-bootstrap5-admin-template-2022-08-08-06-06-10-utc/main-files/demo/vertical/assets/css/semi-dark.css',
    'C:/laragon/www/Ticketing Bluvocation/syndron-bootstrap5-admin-template-2022-08-08-06-06-10-utc/main-files/demo/vertical/assets/css/header-colors.css',
];

$stylesheet = '';

// Load each CSS file and append to stylesheet variable
foreach ($cssFiles as $file) {
    $stylesheet .= file_get_contents($file) . "\n";
}

// Write CSS styles to the PDF
$mpdf->WriteHTML($stylesheet, 1);

// Prepare the HTML content for the invoice
$html = <<<EOD
<style>
$stylesheet;
</style>
<div class="invoice-header">
    <img src="../assets/images/BFF-logo.png" alt="Bluvocation Film Fest Logo" />
</div>
<div>
    <h2 class="name">Bluvocation Film Fest</h2>
    <div>Jl. Raden Saleh, Kec. Karang Tengah, Kota Tangerang, Banten 15157</div>
    <div>021-73455777</div>
    <div>smk@budiluhur.sch.id</div>
</div>
<div>
    <h2 class="to">INVOICE TO:</h2>
    <h3>{$fullName}</h3>
    <div class="address">{$phoneNumber}</div>
    <div class="email"><a href="mailto:{$email}">{$email}</a></div>
</div>
<h1 class="invoice-id">INVOICE #{$paymentDetails['id']}</h1>
<div class="date">Date of Invoice: {$paymentDetails['created_at']}</div>
<div class="date">Due Date: 12/12/2024</div>
<table class="invoice-details" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="padding: 10px; border: 1px solid #ddd;">#</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">DESCRIPTION</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: right;">PRICE</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: right;">QUANTITY</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: right;">TOTAL</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="no">1</td>
            <td class="text-left"><h3>{$orderDescription}</h3></td>
            <td class="unit">{$seatPerUnit}</td>
            <td class="qty">$seatQty</td>
            <td class="total">{$totalAmount}</td>
        </tr>
EOD;

// Display package details if applicable
if ($packageAmount > 0) {
    $html .= <<<EOD
        <tr>
            <td class="no">2</td>
            <td class="text-left"><h3>{$packageDescription}</h3></td>
            <td class="unit">{$packageAmount}</td>
            <td class="qty">1</td>
            <td class="total">{$packageAmount}</td>
        </tr>
EOD;
}

$html .= <<<EOD
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2"></td>
            <td colspan="2">SUBTOTAL</td>
            <td>{$subTotal}</td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td colspan="2">TAX 0%</td>
            <td>{$totalTax}</td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td colspan="2">GRAND TOTAL</td>
            <td>{$grandTotal}</td>
        </tr>
    </tfoot>
</table>
<div class="thanks">Thank you!</div>
<div class="notices">
    <div>NOTICE:</div>
    <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
</div>
<footer>Invoice was created on a computer and is valid without the signature and seal.</footer>
EOD;

// Output the HTML content
$mpdf->WriteHTML($html);

// Close and output PDF document
$mpdf->Output('invoice.pdf', 'I');
?>
