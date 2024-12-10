<?php

include '../include/connection/connection.php';
include '../include/connection/session.php';

// Fetch the user's order history from the `payments` table
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM payments WHERE full_name = (SELECT full_name FROM users WHERE id = ?) ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php
if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
    unset($_SESSION['success']);
}
?>

<?php include '../include/head.php'; ?>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <?php
        if ($_SESSION['role'] === 'admin') {
            include '../include/admin/sidebar.php';
        } else {
            include '../include/user/sidebar.php';
        }
        ?>
        <!--end sidebar wrapper -->
        <!--start header -->
        <?php
        if ($_SESSION['role'] === 'admin') {
            include '../include/admin/header.php';
        } else {
            include '../include/user/header.php';
        }
        ?>
        <!--end header -->
        <!-- Start of Page Wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <h2 class="mb-0 text-uppercase">Order History</h2>
                <hr>

                <?php while ($order = $result->fetch_assoc()): ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="row row-cols-1 row-cols-md-1">
                                <div class="col">
                                    <div class="card p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bx bx-shopping-bag me-2"></i>
                                            <h6 class="mb-0">Order</h6>
                                            <span class="text-muted ms-2"><?= date('d M Y', strtotime($order['created_at'])) ?></span>
                                            <span class="badge bg-<?= $order['status'] == 'approved' ? 'success' : ($order['status'] == 'pending' ? 'warning' : 'danger') ?> ms-3">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </div>
                                        <hr>
                                        <div class="row g-0 align-items-center">
                                            <div class="col-md-2">
                                                <img src="../uploads/<?= htmlspecialchars($order['receipt_image']) ?>" alt="Product" class="img-fluid">
                                            </div>
                                            <div class="col-md-6">
                                                <div class="ps-3">
                                                    <h5 class="card-title mb-1">Bluvocation Film Fest</h5>
                                                    <p class="card-text mb-1">Seats: <?= htmlspecialchars($order['selected_seats']) ?></p>
                                                    
                                                    <!-- Tampilkan Detail Paket per Kursi -->
                                                    <?php
                                                    $packageDetails = json_decode($order['package_details'], true);
                                                    $totalSeats = count($packageDetails);
                                                    $totalPackageCost = 0;

                                                    foreach ($packageDetails as $detail) {
                                                        $seat = htmlspecialchars($detail['seat']);
                                                        if ($detail['package']) {
                                                            echo "<p class='card-text mb-1'>Seat $seat: Package Plus (Add Ons Rp 50.000 - Makanan & Minuman)</p>";
                                                            $totalPackageCost += 50000;
                                                        } else {
                                                            echo "<p class='card-text mb-1'>Seat $seat: No Package</p>";
                                                        }
                                                    }
                                                    ?>
                                                    <p class="card-text mb-0 mt-5">
                                                        <?php if ($order['status'] == 'approved'): ?>
                                                            <a href="../invoice/?full_name=<?= urlencode($order['full_name']) ?>&id=<?= $order['id'] ?>" class="text-primary">Download Invoice</a>
                                                        <?php elseif ($order['status'] == 'pending'): ?>
                                                            <span class="text-warning">Your invoice will be available when your payment is approved</span>
                                                        <?php elseif ($order['status'] == 'rejected'): ?>
                                                            <span class="text-danger">Your payment was rejected. Please contact us.</span>
                                                        <?php endif; ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <!-- Total Amount Calculation -->
                                                <p class="mb-1">Base Total: Rp <?= number_format($order['amount'] - $totalPackageCost, 0, ',', '.') ?></p>
                                                <p class="mb-1">Package Total: Rp <?= number_format($totalPackageCost, 0, ',', '.') ?></p>
                                                <h5 class="text-dark">Total: Rp <?= number_format($order['amount'], 0, ',', '.') ?></h5>
                                                <a href="../order/" class="btn btn-primary btn-sm mt-5">Buy Again</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <!-- End of Page Wrapper -->

        <?php
        // Close the statement and connection
        $stmt->close();
        $conn->close();
        ?>
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        <footer class="page-footer">
            <p class="mb-0">Copyright � 2021. All right reserved.</p>
        </footer>
    </div>
    <!--end wrapper-->

    <?php include '../include/bootstrap-script.php'; ?>
</body>

</html>
