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

    <label class="form-label text-dark mt-0 ms-1">BNI 1503454672 a/n Pakarti Luhur</label>

    <div class="input-group">
        <input class="form-control" name="receipt" type="file" id="receipt">
    </div>
    <br>

    <!-- Informasi Kursi yang Dipilih dalam Input Group -->
    <div class="input-group mb-3">
        <span class="input-group-text">Selected Seats</span>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($_POST['selectedSeats']); ?>" readonly>
    </div>

    <div class="input-group mb-3">
        <span class="input-group-text">Total</span>
        <input type="text" id="amount" name="amount"
            value="<?php echo count(explode(',', $_POST['selectedSeats'])) * 40000; ?> IDR" class="form-control" readonly>
    </div>

    <!-- Package Selection -->
    <h5>Choose Package (Optional):</h5>
    <div class="mb-3">
        <input type="radio" id="packageA" name="packages" value="10000" onchange="updateTotal()">
        <label for="packageA">Package A (Add Ons Rp 10.000 - Biscuit, Merch)</label><br>

        <input type="radio" id="packageB" name="packages" value="20000" onchange="updateTotal()">
        <label for="packageB">Package B (Add Ons Rp 20.000 - Beverages, Merch)</label><br>

        <input type="radio" id="packageC" name="packages" value="30000" onchange="updateTotal()">
        <label for="packageC">Package C (Add Ons Rp 30.000 - Snacks, Beverages, Merch)</label><br>

        <input type="radio" id="noPackage" name="packages" value="0" onchange="updateTotal()" checked>
        <label for="noPackage">None</label>
    </div>

    <input type="hidden" name="selectedSeats" value="<?php echo htmlspecialchars($_POST['selectedSeats']); ?>">

    <div class="col">
        <button type="submit" class="btn btn-primary px-5">
            <i class="bx bx-cloud-upload mr-1"></i>Sent Payment
        </button>
    </div>
</form>
            </div>
        </div>
    </div>

    <script>
    // Function to update the total price based on selected packages
    function updateTotal() {
        const seatPrice = 40000;
        const selectedPackageValue = parseInt(document.querySelector('input[name="packages"]:checked').value) || 0;

        // Calculate the total price
        const seatCount = document.querySelector('input[name="selectedSeats"]').value.split(',').length;
        const totalAmount = (seatCount * seatPrice) + selectedPackageValue;

        // Update the total amount display without formatting
        document.getElementById('amount').value = `${totalAmount} IDR`;
    }
</script>


    <?php include '../include/bootstrap-script.php'; ?>
</body>

</html>