<!-- HTML Head -->
<?php include '../include/head.php'; ?>
<?php include '../include/connection/connection.php'; ?>
<?php include '../include/connection/session.php';
$fullName = isset($_GET['full_name']) ? $_GET['full_name'] : '';
$payment_id = isset($_GET['id']) ? $_GET['id'] : '';


?>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Payment Invoice</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Invoice</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <!-- <div class="toolbar hidden-print">
                            <div class="col-sm-12 col-md-6">
                                <div class="dt-buttons btn-group">      
                                    <button onclick="window.location.href='export-pdf.php?full_name=<?php echo $fullName ?>'" class="btn btn-outline-primary buttons-pdf buttons-html5" tabindex="0" aria-controls="example2" type="button"><span>Download PDF</span></button>                                         </div>
                                    </div>
								<hr>
							</div> -->
                    <?php

                    if ($fullName) {
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
                        $stmt = $conn->prepare("SELECT * FROM payments WHERE full_name = ? AND id = ?");
                        $stmt->bind_param('si', $fullName, $payment_id);
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
                                    $totalAmount += $priceDetails['price'] *  count($selectedSeats) ;
                                }
                            }

                            // Fetch package details if selected
                            $packageAmount = $paymentDetails['package'];
                            $packageDescription = '';
                            if ($packageAmount > 0) {
                                switch ($packageAmount) {
                                    case 50000:
                                        $packageDescription = "Package (Add Ons Rp 50.000 - Snack, Beverages)";
                                        break;
                                }
                            }
                            $subTotal = $totalAmount + $packageAmount;
                            $totalTax = $subTotal * 0;
                            $grandTotal = $subTotal + $totalTax;
                            $orderDescription = "Bluvocation Film Fest Seats: " . implode(", ", $selectedSeats);
                        } else {
                            die('No payments found for this user');
                        }
                    } else {
                        die('Full name is required');
                    }
                    ?>

                    <div id="invoice">
                        <div class="invoice overflow-auto">
                            <div>
                                <header>
                                    <div class="row">
                                        <div class="col">
                                            <a href="javascript:;">
                                                <img src="../assets/images/BFF-logo.png" width="170" alt="" />
                                            </a>
                                        </div>
                                        <div class="col company-details">
                                            <h2 class="name">
                                                <a href="javascript:;">Bluvocation Film Fest</a>
                                                
                                            </h2>
                                            <div>Jl. Raden Saleh, Kec. Karang Tengah, Kota Tangerang, Banten 15157</div>
                                            <div>021-73455777</div>
                                            <div>smk@budiluhur.sch.id</div>
                                        </div>
                                    </div>
                                </header>
                                <main>
                                    <div class="row contacts">
                                        <div class="col invoice-to">
                                            <div class="text-gray-light">INVOICE TO:</div>
                                            <h2 class="to"><?php echo htmlspecialchars($fullName); ?></h2>
                                            <div class="address"><?php echo htmlspecialchars($phoneNumber); ?></div>
                                            <div class="email"><a href="mailto:<?php echo htmlspecialchars($email); ?>"><?php echo htmlspecialchars($email); ?></a></div>
                                        </div>
                                        <div class="col invoice-details">
                                            <h1 class="invoice-id">INVOICE #<?php echo $paymentDetails['id']; ?></h1>
                                            <div class="date">Date of Invoice: <?php echo date('d/m/Y', strtotime($paymentDetails['created_at'])); ?></div>
                                            <div class="date">Due Date: 12/12/2024</div>
                                        </div>
                                    </div>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="text-left">DESCRIPTION</th>
                                                <th class="text-right">PRICE</th>
                                                <th class="text-right">QUANTITY</th>
                                                <th class="text-right">TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $rowNumber = 1;

                                            // Display seats description
                                            echo "<tr>";
                                            echo "<td class='no'>{$rowNumber}</td>";
                                            echo "<td class='text-left'><h3>{$orderDescription}</h3></td>";
                                            echo "<td class='unit'>" . htmlspecialchars($priceDetails['price']) . "</td>";
                                            echo "<td class='qty'>" . count($selectedSeats) . "</td>";
                                            echo "<td class='total'>" . htmlspecialchars($totalAmount) . "</td>";
                                            echo "</tr>";

                                            // Display package details if applicable
                                            if ($packageAmount > 0) {
                                                $rowNumber++;
                                                echo "<tr>";
                                                echo "<td class='no'>{$rowNumber}</td>";
                                                echo "<td class='text-left'><h3>{$packageDescription}</h3></td>";
                                                echo "<td class='unit'>" . $paymentDetails['package'] . "</td>";
                                                echo "<td class='qty'>1</td>";
                                                echo "<td class='total'>" . $paymentDetails['package'] . "</td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td colspan="2">SUBTOTAL</td>
                                                <td><?php echo htmlspecialchars($subTotal); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td colspan="2">TAX 0%</td>
                                                <td><?php echo htmlspecialchars($totalTax); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td colspan="2">GRAND TOTAL</td>
                                                <td><?php echo htmlspecialchars($grandTotal); ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div class="thanks">Thank you!</div>
                                    <div class="notices">
                                        <div>NOTICE:</div>
                                        <div class="notice">Show this invoice as a proof that you have been order the seats</div>
                                    </div>
                                    <?php
                                    $stmt = $conn->prepare("SELECT status, qr_token FROM `qr-codes` WHERE invoice_id = ?");
                                    $stmt->bind_param('i', $payment_id);
                                    $stmt->execute();
                                    $stmt->bind_result($status, $qr_token);
                                    $stmt->fetch();
                                    $stmt->close();

                                    // Generate the path to the QR code image
                                    $qr_image_path = "../assets/images/qrcodes/" . $qr_token . ".png"; // Adjust path as necessary
                                    ?>
                                    <?php if ($qr_token): ?>
                                        <img src="<?php echo htmlspecialchars($qr_image_path); ?>" alt="QR Code for Invoice" class="img-fluid" />
                                    <?php else: ?>
                                        <p>No QR code generated for this invoice.</p>
                                    <?php endif; ?>
                            </div>
                            </main>
                            <footer>Invoice was created on a computer and is valid without the signature and seal.</footer>
                        </div>
                        <div></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--start overlay-->
    <div class="overlay toggle-icon"></div>
    <!--end overlay-->
    <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
    </div>
    <!--end wrapper-->

    <?php include '../include/bootstrap-script.php'; ?>
</body>

</html>