<?php
include '../include/connection/connection.php';
include '../include/connection/session.php';
?>
<?php include '../include/head.php'; ?>

<body>
    <div class="page-content">
        <h2>Payment Form</h2>
        <hr>

        <div class="card">
            <div class="card-body">
                <?php
                if (!isset($_POST['selectedSeats'])) {
                    echo "<p class='text-danger'>Kursi belum dipilih. Silakan pilih kursi terlebih dahulu.</p>";
                    exit();
                }

                $selectedSeats = explode(',', $_POST['selectedSeats']);
                $baseTotal = 0;

                // Hitung Base Total dengan mempertimbangkan harga per baris
                foreach ($selectedSeats as $seat) {
                    $row = strtoupper(substr($seat, 0, 1)); // Ambil huruf baris dan pastikan uppercase
                    if ($row === 'A') {
                        $baseTotal += 65000; // Harga premium untuk kursi di baris A
                    } else {
                        $baseTotal += 50000; // Harga reguler untuk kursi lainnya
                    }
                }
                
                ?>

                <form method="POST" action="submit_receipt.php" enctype="multipart/form-data" id="paymentForm">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Full Name</span>
                        <input type="text" id="fullName" name="fullName" class="form-control" value="<?php echo $_SESSION['full_name']; ?>" readonly>
                    </div>

                    <div class="input-group mb-1">
                        <select class="form-select" id="paymentMethod" name="paymentMethod" required>
                            <option value="">--Select payment method --</option>
                            <option value="transfer_bank">Bank Transfer</option>
                            <option value="ewallet">E-Wallet</option>
                        </select>
                    </div>

                    <label class="form-label text-dark mt-0 ms-1">BCA 7010569120 a/n Devara Widyawan</label>

                    <div class="input-group">
                        <input class="form-control" name="receipt" type="file" id="receipt" required>
                    </div>
                    <br>

                    <!-- Informasi Kursi yang Dipilih dalam Input Group -->
                    <div class="input-group mb-3">
                        <span class="input-group-text">Selected Seats</span>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($_POST['selectedSeats']); ?>" readonly>
                    </div>

                    <!-- Total tanpa paket -->
                    <div class="input-group mb-3">
                        <span class="input-group-text">Base Total</span>
                        <input type="text" id="baseAmount" name="baseAmount" 
                               value="<?php echo $baseTotal; ?>" 
                               class="form-control" readonly>
                    </div>

                    <!-- Package Selection Per Kursi -->
                    <h5>Choose Package for Each Seat (Optional):</h5>
                    <div id="packageSelectionContainer">
                        <?php foreach ($selectedSeats as $index => $seat): 
                            $row = strtoupper(substr($seat, 0, 1)); // Ambil huruf baris untuk kursi ini
                            $seatPrice = ($row === 'A') ? 65000 : 50000; // Harga khusus untuk kursi ini
                        ?>
                            <div class="mb-3">
                                <label for="package_<?php echo $index; ?>" class="form-label">Seat <?php echo $seat; ?>:</label>
                                <select id="package_<?php echo $index; ?>" name="packages[<?php echo $index; ?>]" class="form-select package-option" data-seat-price="<?php echo $seatPrice; ?>" onchange="updateTotal()">
                                    <option value="0">None</option>
                                    <option value="50000">Package Plus (Add Ons Rp 50.000 - Makanan & Minuman)</option>
                                </select>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Total Amount -->
                    <div class="input-group mb-3">
                        <span class="input-group-text">Total Amount</span>
                        <input type="text" id="amount" name="amount" 
                               value="<?php echo $baseTotal; ?>" 
                               class="form-control" readonly>
                    </div>

                    <input type="hidden" name="selectedSeats" value="<?php echo htmlspecialchars($_POST['selectedSeats']); ?>">

                    <div class="col">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bx bx-cloud-upload mr-1"></i>Send Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Function to update the total price based on selected packages for each seat
    function updateTotal() {
        let totalAmount = 0;

        // Loop through all seat selections
        document.querySelectorAll('.package-option').forEach(select => {
            const basePrice = parseInt(select.getAttribute('data-seat-price')) || 0; // Base price per seat
            const packagePrice = parseInt(select.value) || 0; // Package price (if selected)
            totalAmount += basePrice + packagePrice;
        });

        // Update the total amount display
        document.getElementById('amount').value = totalAmount;
    }
    </script>

    <?php include '../include/bootstrap-script.php'; ?>
</body>

</html>
