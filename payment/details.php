<!-- HTML Head -->
<?php include '../include/head.php'; ?>
<?php include '../include/connection/session.php';

if ($_SESSION['role'] != 'admin') {
    // If the user is an admin, redirect to the dashboard page
    header('Location: ../order/');
    exit();
}

include '../include/connection/connection.php';

// Get the id from the URL
$payment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Check if id exists
if ($payment_id > 0) {
    // Query to fetch payment details and join with users table to get phone and email
    $query = "SELECT payments.full_name, payments.payment_method, payments.selected_seats, payments.amount, payments.status, 
                        payments.receipt_image, users.phone_number, users.email 
                FROM payments 
                LEFT JOIN users ON payments.full_name = users.full_name 
                WHERE payments.id = $payment_id";
    $result = $conn->query($query);

    // Check if any record is returned
    if ($result && $result->num_rows > 0) {
        $payment_details = $result->fetch_assoc();
    } else {
        echo "No payment details found.";
        exit;
    }
} else {
    echo "Invalid payment ID.";
    exit;
}
?>


<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <?php include '../include/admin/sidebar.php'; ?>
        <!--end sidebar wrapper -->
        <!--start header -->
        <?php include '../include/admin/header.php'; ?>
        <!--end header -->
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <!--breadcrumb-->
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Payment</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Payment</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!--end breadcrumb-->
                <div class="container">
                    <div class="main-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex flex-column align-items-center text-center">
                                            <h4>Payment Screenshot</h4>
                                            <img src="../uploads/<?php echo htmlspecialchars($payment_details['receipt_image']); ?>" class="img-fluid" alt="Payment Screenshot">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="d-flex align-items-center mb-3">Customer Data</h5>
                                        <form id="profile-form" method="POST" action="logic.php" enctype="multipart/form-data">
                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Full Name</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <input id="full_name" class="form-control mb-0" type="text" name="full_name" value="<?php echo htmlspecialchars($payment_details['full_name']); ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Payment Method</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <input id="payment_method" class="form-control mb-0" type="text" name="payment_method" value="<?php echo htmlspecialchars($payment_details['payment_method']); ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Selected Seats</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <input id="selected_seats" class="form-control mb-0" type="text" name="selected_seats" value="<?php echo htmlspecialchars($payment_details['selected_seats']); ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Total</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <input id="amount" class="form-control mb-0" type="text" name="amount" value="<?php echo htmlspecialchars($payment_details['amount']); ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Phone Number</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <input id="phone_number" class="form-control mb-0" type="text" name="phone_number" value="<?php echo htmlspecialchars($payment_details['phone_number']); ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-3">
                                                    <h6 class="mb-0">Email</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <input id="email" class="form-control mb-0" type="email" name="email" value="<?php echo htmlspecialchars($payment_details['email']); ?>" disabled>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-body">
                                        <form id="profile-form" method="POST" action="logic.php" enctype="multipart/form-data">
                                            <h5 class="d-flex align-items-center mb-3">Approve Payment?</h5>
                                            <div class="row row-cols-auto g-3">
                                                <div class="col">
                                                    <!-- Button trigger modal for Approve -->
                                                    <?php if($payment_details['status'] !== "pending"){
                                                        $buttonStatus = "disabled";
                                                        $cancelButtonClass = "primary";
                                                        $approveButtonClass = "secondary";
                                                        $rejectButtonClass = "secondary";
                                                    }else {
                                                        $buttonStatus = "";
                                                        $cancelButtonClass = "primary";
                                                        $approveButtonClass = "primary";
                                                        $rejectButtonClass = "danger";
                                                    }?>
                                                    <button type="button" class="btn btn-<?= $approveButtonClass ?> px-5" data-bs-toggle="modal" data-bs-target="#approvalModal" onclick="setModalData('approve', <?php echo $payment_id; ?>)" <?= $buttonStatus ?>>Approve</button>
                                                    <!-- Approval Modal -->
                                                    <div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="approvalModalLabel">Approval Confirmation</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">Are you sure you want to approve this payment?</div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <button type="button" class="btn btn-primary" id="approve-btn" onclick="updateStatus('approve')">Approve</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <button type="button" class="btn btn-<?= $rejectButtonClass ?>" data-bs-toggle="modal" data-bs-target="#rejectionModal" onclick="setModalData('reject', <?php echo $payment_id; ?>)" <?= $buttonStatus ?>>Reject</button>
                                                    <!-- Rejection Modal -->
                                                    <div class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="rejectionModalLabel">Rejection Confirmation</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">Are you sure you want to reject this payment?</div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <button type="button" class="btn btn-danger" id="reject-btn" onclick="updateStatus('reject')">Reject</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col"><a href="../payment/" id="save-btn" class="btn btn-<?= $cancelButtonClass ?>">Cancel</a></div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end page wrapper -->
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        <footer class="page-footer">
            <p class="mb-0">Copyright © 2021. All right reserved.</p>
        </footer>
    </div>
    <!--end wrapper-->
    <!--start switcher-->
    <div class="switcher-wrapper">
        <div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
        </div>
        <div class="switcher-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-uppercase">Theme Customizer</h5>
                <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
            </div>
            <hr />
            <h6 class="mb-0">Theme Styles</h6>
            <hr />
            <div class="d-flex align-items-center justify-content-between">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="lightmode" checked>
                    <label class="form-check-label" for="lightmode">Light</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="darkmode">
                    <label class="form-check-label" for="darkmode">Dark</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="semidark">
                    <label class="form-check-label" for="semidark">Semi Dark</label>
                </div>
            </div>
            <hr />
            <div class="form-check">
                <input class="form-check-input" type="radio" id="minimaltheme" name="flexRadioDefault">
                <label class="form-check-label" for="minimaltheme">Minimal Theme</label>
            </div>
            <hr />
            <h6 class="mb-0">Header Colors</h6>
            <hr />
            <div class="header-colors-indigators">
                <div class="row row-cols-auto g-3">
                    <div class="col">
                        <div class="indigator headercolor1" id="headercolor1"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor2" id="headercolor2"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor3" id="headercolor3"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor4" id="headercolor4"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor5" id="headercolor5"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor6" id="headercolor6"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor7" id="headercolor7"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor8" id="headercolor8"></div>
                    </div>
                </div>
            </div>
            <hr />
            <h6 class="mb-0">Sidebar Backgrounds</h6>
            <hr />
            <div class="header-colors-indigators">
                <div class="row row-cols-auto g-3">
                    <div class="col">
                        <div class="indigator sidebarcolor1" id="sidebarcolor1"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor2" id="sidebarcolor2"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor3" id="sidebarcolor3"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor4" id="sidebarcolor4"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor5" id="sidebarcolor5"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor6" id="sidebarcolor6"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor7" id="sidebarcolor7"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor8" id="sidebarcolor8"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end switcher-->
    <!-- Bootstrap JS -->
    <script>
        let currentPaymentId;

// Function to set the modal data
function setModalData(action, paymentId) {
    currentPaymentId = paymentId;

    if (action === 'approve') {
        document.getElementById('approvalModalLabel').innerText = 'Approval Confirmation';
        document.querySelector('.modal-body').innerText = 'Are you sure you want to approve this payment?';
    } else if (action === 'reject') {
        document.getElementById('rejectionModalLabel').innerText = 'Rejection Confirmation';
        document.querySelector('.modal-body').innerText = 'Are you sure you want to reject this payment?';
    }
}

// Function to update payment status
function updateStatus(action) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'logic.php', true); // Adjust the URL to your PHP file handling the update
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Optionally, show a success message or refresh the page
                alert(`Payment has been ${action} successfully.`);
                window.location.href = '../payment/';
            } else {
                alert('There was an error updating the payment status.');
            }
        }
    };

    xhr.send(`id=${currentPaymentId}&action=${action}`); // Send payment ID and action (approve/reject)
}

    </script>
    <?php include '../include/bootstrap-script.php'; ?>

</body>

</html>