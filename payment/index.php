<?php include '../include/connection/connection.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 ?>
<?php include '../include/connection/session.php'; ?>

<!-- HTML Head -->
<?php include '../include/head.php'; ?>

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
                    <div class="breadcrumb-title pe-3">Payment Data</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="../dashboard/"><i class="bx bx-home-alt"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Payment Data</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!--end breadcrumb-->
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
                    $search_condition = '';
                    if (!empty($search_query)) {
                        $search_query = $conn->real_escape_string($search_query);
                        $search_condition = "WHERE full_name LIKE '%$search_query%' 
                                             OR payment_method LIKE '%$search_query%' 
                                             OR selected_seats LIKE '%$search_query%'
                                             OR status LIKE '%$search_query%'";
                    }

                    // Calculate the total number of payments (including the search condition)
                    $sql_count = "SELECT COUNT(*) as total FROM payments $search_condition";
                    $result_count = $conn->query($sql_count);
                    $total_payments = ($result_count->num_rows > 0) ? $result_count->fetch_assoc()['total'] : 0;

                    // Calculate total pages
                    $total_pages = ceil($total_payments / $entries_per_page);

                    // Fetch payments from the database with pagination and search
                    $offset = ($current_page - 1) * $entries_per_page;
                    $sql = "SELECT id, full_name, payment_method, selected_seats, amount, status, created_at, receipt_image 
                            FROM payments 
                            $search_condition 
                            ORDER BY created_at DESC 
                            LIMIT $offset, $entries_per_page";
                    $result = $conn->query($sql);
                    ?>

                    <div class="card-body">
                        <div class="toolbar hidden-print">
                            <div class="col-sm-12 col-md-6">
                                <div class="dt-buttons btn-group">      
                                    <button onclick="window.location.href='export-excel.php'" class="btn btn-outline-secondary buttons-excel buttons-html5" tabindex="0" aria-controls="example2" type="button"><span>Excel</span></button> 
                                    <button onclick="window.location.href='export-pdf.php'" class="btn btn-outline-secondary buttons-pdf buttons-html5" tabindex="0" aria-controls="example2" type="button"><span>Download PDF</span></button> 
                                    <button onclick="window.location.href='preview-pdf.php'" class="btn btn-outline-secondary buttons-print" tabindex="0" aria-controls="example2" type="button"><span>Preview PDF</span></button> 
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div class="table-responsive">
                            <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap5">
                                <div class="row mt-1 mb-2">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="dataTables_length" id="example_length">
                                            <form method="GET" action="">
                                                <label>Show
                                                    <select name="entries" class="form-select form-select-sm" onchange="this.form.submit()">
                                                        <option value="10" <?php echo ($entries_per_page == 10 ? 'selected' : ''); ?>>10</option>
                                                        <option value="25" <?php echo ($entries_per_page == 25 ? 'selected' : ''); ?>>25</option>
                                                        <option value="50" <?php echo ($entries_per_page == 50 ? 'selected' : ''); ?>>50</option>
                                                        <option value="100" <?php echo ($entries_per_page == 100 ? 'selected' : ''); ?>>100</option>
                                                    </select> entries
                                                </label>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div id="example_filter" class="dataTables_filter">
                                            <form method="GET" action="">
                                                <label>Search:
                                                    <input type="search" name="search" class="form-control form-control-sm" placeholder="Search..." value="<?php echo htmlspecialchars($search_query); ?>" onkeypress="if(event.key === 'Enter'){ this.form.submit(); }">
                                                </label>
                                                <input type="hidden" name="entries" value="<?php echo $entries_per_page; ?>">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Order Id</th>
                                                    <th>Full Name</th>
                                                    <th>Status</th>
                                                    <th>Selected Seats</th>
                                                    <th>Payment Method</th>
                                                    <th>Total</th>
                                                    <th>Date</th>
                                                    <th>View Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                ?>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <input class="form-check-input me-3" type="checkbox" value="" aria-label="...">
                                                                    </div>
                                                                    <div class="ms-2">
                                                                        <h6 class="mb-0 font-14">#<?php echo htmlspecialchars($row['id']) ?></h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($row['full_name']) ?></td>
                                                            <td>
                                                                <?php
                                                                $status_class = '';
                                                                $icon_color = '';

                                                                if ($row['status'] === 'pending') {
                                                                    $status_class = 'text-warning bg-light-warning';
                                                                    $icon_color = 'text-warning';
                                                                } elseif ($row['status'] === 'rejected') {
                                                                    $status_class = 'text-danger bg-light-danger';
                                                                    $icon_color = 'text-danger';
                                                                } elseif ($row['status'] === 'approved') {
                                                                    $status_class = 'text-success bg-light-success';
                                                                    $icon_color = 'text-success';
                                                                }
                                                                ?>
                                                                <div class="badge rounded-pill <?php echo $status_class; ?> p-2 text-uppercase px-3">
                                                                    <i class="bx bxs-circle me-1 <?php echo $icon_color; ?>"></i>
                                                                    <?php echo htmlspecialchars($row['status']); ?>
                                                                </div>
                                                            </td>

                                                            <td><?php echo htmlspecialchars($row['selected_seats']) ?></td>
                                                            <td><?php echo htmlspecialchars($row['payment_method']) ?></td>
                                                            <td>Rp <?php echo htmlspecialchars($row['amount']) ?></td>
                                                            <td><?php echo htmlspecialchars($row['created_at']) ?></td>
                                                            <td><a href="details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm radius-30 px-4">
                                                                Details
                                                                </a>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo '<tr><td colspan="8">No payments found.</td></tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-12 col-md-5">
                                        <div class="dataTables_info">
                                            Showing <?php echo ($offset + 1); ?> to <?php echo min($offset + $entries_per_page, $total_payments); ?> of <?php echo $total_payments; ?> entries
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-7">
                                        <div class="dataTables_paginate paging_simple_numbers">
                                            <ul class="pagination">
                                                <?php
                                                // Previous button
                                                if ($current_page > 1) {
                                                    echo '<li class="paginate_button page-item previous"><a href="?page=' . ($current_page - 1) . '&entries=' . $entries_per_page . '&search=' . urlencode($search_query) . '" class="page-link">Previous</a></li>';
                                                }

                                                // Page numbers
                                                for ($i = 1; $i <= $total_pages; $i++) {
                                                    $active = $i === $current_page ? 'active' : '';
                                                    echo '<li class="paginate_button page-item ' . $active . '"><a href="?page=' . $i . '&entries=' . $entries_per_page . '&search=' . urlencode($search_query) . '" class="page-link">' . $i . '</a></li>';
                                                }

                                                // Next button
                                                if ($current_page < $total_pages) {
                                                    echo '<li class="paginate_button page-item next"><a href="?page=' . ($current_page + 1) . '&entries=' . $entries_per_page . '&search=' . urlencode($search_query) . '" class="page-link">Next</a></li>';
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
        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class="bx bxs-up-arrow-alt"></i></a>
    </div>
    <!--end wrapper-->
    <!-- Bootstrap JS -->
    <?php include '../include/bootstrap-script.php'; ?>

</body>
</html>