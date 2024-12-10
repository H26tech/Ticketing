<!-- HTML Head -->
<?php include '../include/head.php'; ?>
<style>
    #reader {
        width: 600px;
        height: 400px;
        border: 1px solid #ccc;
        display: none;
        /* Initially hidden */
    }
</style>
<script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
<script src="..\include\html5-qrcode-master\minified\html5-qrcode.min.js"></script>
<?php include '../include/connection/connection.php'; ?>
<?php include '../include/connection/session.php'; ?>

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
                    <div class="breadcrumb-title pe-3">Scan Invoice</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="../dashboard/"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Scan</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!--end breadcrumb-->
                <div class="col">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#qrScannerModal">Open QR Scanner</button>

                    <!-- Modal -->
                    <div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="qrScannerModalLabel">Scan QR Code</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="reader" style="width: 100%; height: 400px; display: flex; justify-content: center; align-items: center; border: 1px solid #ccc;"></div>
                                    <div id="qr-reader-results"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Modal -->
                <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="dynamicModalTitle" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="dynamicModalTitle"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="dynamicModalContent">
                                <!-- Content will be injected here based on scan status -->
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card">

                    <?php
                    // Default values
                    $default_entries_per_page = 10;
                    $entries_per_page = isset($_GET['entries']) ? (int)$_GET['entries'] : $default_entries_per_page;

                    // Get the current page number
                    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

                    // Ensure the current page is at least 1
                    $current_page = max($current_page, 1);

                    // Handle search query
                    $search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

                    // Prepare the SQL search condition
                    $search_condition = "WHERE status = 'used'";
                    if (!empty($search_query)) {
                        $search_query = $conn->real_escape_string($search_query);
                        $search_condition .= " AND (qr_token LIKE '%$search_query%' 
                                    OR invoice_id LIKE '%$search_query%' 
                                    OR scanned_by LIKE '%$search_query%')";
                    }

                    // Calculate the total number of QR codes
                    $sql_count = "SELECT COUNT(*) as total FROM `qr-codes` $search_condition";
                    $result_count = $conn->query($sql_count);
                    $total_qr_codes = ($result_count->num_rows > 0) ? $result_count->fetch_assoc()['total'] : 0;

                    // Calculate total pages
                    $total_pages = ceil($total_qr_codes / $entries_per_page);

                    // Fetch QR codes from the database with pagination and search
                    $offset = ($current_page - 1) * $entries_per_page;
                    $sql = "SELECT qr_token, invoice_id, scanned_by, status, created_at 
            FROM `qr-codes` 
            $search_condition 
            LIMIT $offset, $entries_per_page";
                    $result = $conn->query($sql);
                    ?>

                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap5">
                                <!-- Table and Pagination UI remain unchanged -->
                                <table id="example" class="table table-striped table-bordered dataTable" role="grid">
                                    <thead>
                                        <tr role="row">
                                            <th>QR Code</th>
                                            <th>Invoice ID</th>
                                            <th>Scanned By</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Check if there are results
                                        if ($result->num_rows > 0) {
                                            // Loop through the results and populate the table rows
                                            while ($row = $result->fetch_assoc()) {
                                                echo '
                                <tr role="row">
                                    <td>' . htmlspecialchars($row['qr_token']) . '</td>
                                    <td>' . htmlspecialchars($row['invoice_id']) . '</td>
                                    <td>' . htmlspecialchars($row['scanned_by']) . '</td>
                                    <td>' . htmlspecialchars(ucfirst($row['status'])) . '</td>
                                    <td>' . htmlspecialchars($row['created_at']) . '</td>
                                </tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="5">No QR codes found.</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>QR Code</th>
                                            <th>Invoice ID</th>
                                            <th>Scanned By</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="example_info" role="status" aria-live="polite">
                                    Showing <?php echo ($offset + 1); ?> to <?php echo min($offset + $entries_per_page, $total_qr_codes); ?> of <?php echo $total_qr_codes; ?> entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="example_paginate">
                                    <ul class="pagination">
                                        <?php
                                        // Previous button
                                        if ($current_page > 1) {
                                            echo '<li class="paginate_button page-item previous"><a href="?page=' . ($current_page - 1) . '&entries=' . $entries_per_page . '&search=' . urlencode($search_query) . '" class="page-link">Prev</a></li>';
                                        } else {
                                            echo '<li class="paginate_button page-item previous disabled"><a href="#" class="page-link">Prev</a></li>';
                                        }

                                        // Page number links
                                        for ($i = 1; $i <= $total_pages; $i++) {
                                            if ($i == $current_page) {
                                                echo '<li class="paginate_button page-item active"><a href="#" class="page-link">' . $i . '</a></li>';
                                            } else {
                                                echo '<li class="paginate_button page-item"><a href="?page=' . $i . '&entries=' . $entries_per_page . '&search=' . urlencode($search_query) . '" class="page-link">' . $i . '</a></li>';
                                            }
                                        }

                                        // Next button
                                        if ($current_page < $total_pages) {
                                            echo '<li class="paginate_button page-item next"><a href="?page=' . ($current_page + 1) . '&entries=' . $entries_per_page . '&search=' . urlencode($search_query) . '" class="page-link">Next</a></li>';
                                        } else {
                                            echo '<li class="paginate_button page-item next disabled"><a href="#" class="page-link">Next</a></li>';
                                        }
                                        ?>
                                    </ul>
                                </div>
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
    <!-- <footer class="page-footer">
            <p class="mb-0">Copyright © 2021. All right reserved.</p>
        </footer> -->
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
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const html5QrcodeScanner = new Html5Qrcode("reader");

        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Code matched = ${decodedText}`, decodedResult);

            // Close the modal after scanning
            const modal = bootstrap.Modal.getInstance(document.getElementById("qrScannerModal"));
            modal.hide();

            // Stop the scanner, then redirect
            setTimeout(() => {
                html5QrcodeScanner.stop()
                    .then(() => {
                        // Once stopped, redirect to process-scan.php with the QR token and admin_id
                        window.location.href = `process-scan.php?qr_token=${encodeURIComponent(decodedText)}&admin_id=<?php echo $_SESSION['user_id']; ?>`;
                    })
                    .catch(err => {
                        console.error("Failed to stop the scanner: ", err);
                        // Redirect anyway if there's an issue stopping the scanner
                        window.location.href = `process-scan.php?qr_token=${encodeURIComponent(decodedText)}&admin_id=<?php echo $_SESSION['user_id']; ?>`;
                    });
            }, 50); // Delay to ensure scanner stop
        }

        function onScanFailure(error) {
            console.warn(`QR code scan error: ${error}`);
        }

        // Start the scanner when the modal is shown
        $('#qrScannerModal').on('shown.bs.modal', function() {
            console.log("Modal shown: Starting QR scanner.");
            const qrboxSize = Math.min(document.getElementById('reader').offsetWidth * 0.8, 250);
            html5QrcodeScanner.start({
                    facingMode: "environment"
                }, {
                    fps: 10,
                    qrbox: {
                        width: qrboxSize,
                        height: qrboxSize
                    }
                },
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                console.error(`Unable to start scanning. Error: ${err}`);
            });
        });

        // Stop the scanner when the modal is hidden
        $('#qrScannerModal').on('hidden.bs.modal', function() {
            console.log("Modal hidden: Stopping QR scanner.");
            html5QrcodeScanner.stop().catch(err => {
                console.error("Failed to stop scanning: ", err);
            });
        });

        // Check for status and message parameters in the URL to show result modal
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get("status");
        const message = urlParams.get("message");

        if (status) {
            let modalTitle = "Scan Result";
            if (status === "success") {
                modalTitle = "Scan Successful";
            } else if (status === "notfound") {
                modalTitle = "QR Code Not Found";
            } else if (status === "error") {
                modalTitle = "Scan Error";
            }

            document.getElementById("dynamicModalTitle").innerText = modalTitle;
            document.getElementById("dynamicModalContent").innerText = decodeURIComponent(message);

            const resultModal = new bootstrap.Modal(document.getElementById("resultModal"));
            resultModal.show();
        }
    });
</script>


    <?php include '../include/bootstrap-script.php'; ?>
</body>

</html>