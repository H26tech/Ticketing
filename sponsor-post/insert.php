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
                                <li class="breadcrumb-item active" aria-current="page">Insert Sponsor</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="logic.php" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Sponsor Name</label>
                                <input type="text" class="form-control" name="nama_sponsor" placeholder="Sponsor Name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sponsor Logo</label>
                                <input type="file" class="form-control" name="foto_logo" required>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../include/bootstrap-script.php'; ?>
</body>
</html>
