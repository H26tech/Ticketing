<!-- HTML Head -->
<?php include '../include/head.php'; ?>
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
                    <div class="breadcrumb-title pe-3">Sponsor Post</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="../dashboard/"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Sponsor Post</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="card">

                    <div class="card-body">
                        <div class="toolbar hidden-print">
                            <div class="col-sm-12 col-md-6">
                                <div class="btn-group">
                                    <a href="insert.php" class="btn btn-primary"><i class="bx bx-plus"></i> Add Sponsor</a>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap5">
                            <div class="row mt-1 mb-2">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_length" id="example_length">
                                        <form method="GET" action="">
                                            <label>Show
                                                <select name="entries" class="form-select form-select-sm" onchange="this.form.submit()" fdprocessedid="0ozwag">
                                                    <option value="10" selected="">10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select> entries
                                            </label>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div id="example_filter" class="dataTables_filter">
                                        <form method="GET" action="">
                                            <label>Search:
                                                <input type="search" name="search" class="form-control form-control-sm" placeholder="Search..." value="" onkeypress="if(event.key === 'Enter'){ this.form.submit(); }">
                                            </label>
                                            <input type="hidden" name="entries" value="10">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap5">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Sponsor ID</th>
                                                    <th>Sponsor Name</th>
                                                    <th>Image</th>
                                                    <th>Actions</th> <!-- Tambahkan kolom untuk aksi -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Query untuk mengambil data dari tabel sponsor
                                                $query = "SELECT id, nama_sponsor, foto_logo FROM sponsor";
                                                $result = mysqli_query($conn, $query);

                                                if ($result && mysqli_num_rows($result) > 0) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row['nama_sponsor']) . "</td>";
                                                        echo "<td><img src='../uploads/" . htmlspecialchars($row['foto_logo']) . "' alt='" . htmlspecialchars($row['nama_sponsor']) . " logo' style='width: 50px; height: auto;'></td>";
                                                        echo "<td>";
                                                        echo "<a href='edit.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'> Edit</a> ";
                                                        echo "<a href='delete.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure to delete this sponsor?\");' class='btn btn-danger btn-sm'> Delete</a>";
                                                        echo "</td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='4'>No sponsor data available</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap5">
                            <div class="row mt-2">
                                <div class="col-sm-12 col-md-5">
                                                                    </div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="dataTables_paginate paging_simple_numbers">
                                        <ul class="pagination">
                                            <li class="paginate_button page-item previous disabled"><a href="#" class="page-link">Prev</a></li>
                                            <li class="paginate_button page-item active"><a href="#" class="page-link">1</a></li>
                                            <li class="paginate_button page-item next disabled"><a href="#" class="page-link">Next</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../include/bootstrap-script.php'; ?>
</body>

</html>