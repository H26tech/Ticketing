<!-- HTML Head -->
<?php include '../include/head.php'; ?>
<?php include '../include/connection/connection.php'; ?>
<?php include '../include/connection/session.php'; ?>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <?php include '../include/admin/sidebar.php';?>
        <!--end sidebar wrapper -->
        <!--start header -->
        <?php include '../include/admin/header.php';?>
        <!--end header -->
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <!--breadcrumb-->
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Users Data</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="../dashboard/"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">User Table</li>
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
        // Use LIKE to search for matching users in the relevant fields
        $search_query = $conn->real_escape_string($search_query); // Sanitize input to avoid SQL injection
        $search_condition = "WHERE username LIKE '%$search_query%' 
                                OR email LIKE '%$search_query%' 
                                OR full_name LIKE '%$search_query%' 
                                OR phone_number LIKE '%$search_query%'";
    }

    // Calculate the total number of users (including the search condition)
    $sql_count = "SELECT COUNT(*) as total FROM users $search_condition";
    $result_count = $conn->query($sql_count);
    $total_users = ($result_count->num_rows > 0) ? $result_count->fetch_assoc()['total'] : 0;

    // Calculate total pages
    $total_pages = ceil($total_users / $entries_per_page);

    // Fetch users from the database with pagination and search
    $offset = ($current_page - 1) * $entries_per_page;
    $sql = "SELECT username, email, full_name, phone_number, role, created_at 
            FROM users 
            $search_condition 
            LIMIT $offset, $entries_per_page";
    $result = $conn->query($sql);
    ?>

    <div class="card-body">
        <div class="table-responsive">
            <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap5">
                <div class="row mt-1">
                    <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length" id="example_length">
                            <form method="GET" action="">
                                <label>Show
                                    <select name="entries" aria-controls="example" class="form-select form-select-sm" onchange="this.form.submit()">
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
                                    <input type="search" name="search" class="form-control form-control-sm" placeholder="Search..." aria-controls="example" value="<?php echo htmlspecialchars($search_query); ?>" onkeypress="if(event.key === 'Enter'){ this.form.submit(); }">
                                </label>
                                <input type="hidden" name="entries" value="<?php echo $entries_per_page; ?>">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table id="example" class="table table-striped table-bordered dataTable" role="grid" aria-describedby="example_info">
                            <thead>
                                <tr role="row">
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Full Name</th>
                                    <th>Phone</th>
                                    <th>Role</th>
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
                                            <td>' . htmlspecialchars($row['username']) . '</td>
                                            <td>' . htmlspecialchars($row['email']) . '</td>
                                            <td>' . htmlspecialchars($row['full_name']) . '</td>
                                            <td>' . htmlspecialchars($row['phone_number']) . '</td>
                                            <td>' . htmlspecialchars(ucfirst($row['role'])) . '</td>
                                            <td>' . htmlspecialchars($row['created_at']) . '</td>
                                        </tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="6">No users found.</td></tr>';
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Full Name</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Created At</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="example_info" role="status" aria-live="polite">
                            Showing <?php echo ($offset + 1); ?> to <?php echo min($offset + $entries_per_page, $total_users); ?> of <?php echo $total_users; ?> entries
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

    <?php include '../include/bootstrap-script.php'; ?>
</body>

</html>