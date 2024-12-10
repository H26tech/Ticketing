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
                    <div class="breadcrumb-title pe-3">Banner Content</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="../dashboard/"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Banner Content</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!--end breadcrumb-->
                <h6 class="mb-0 text-uppercase">All Banner</h6>
                <hr class="mb-1">
                <div class="ms-auto mt-0 mb-2">
                    <div class="btn-group">
                        <?php $query = "SELECT COUNT(*) as total FROM `banner-content`";
                        $result = $conn->query($query);
                        $row = $result->fetch_assoc();
                        $totalBanners = $row['total'];
                        if ($totalBanners < 3): ?>
                            <a href="insert-banner.php" class="btn btn-primary"><i class="bx bx-plus"></i> Add Banner</a>
                        <?php else: ?>
                            <div class="alert alert-warning border-0 bg-warning alert-dismissible fade show py-2 mb-1 mt-2">
									<div class="d-flex align-items-center">
										<div class="font-35 text-dark"><i class="bx bx-info-circle"></i>
										</div>
										<div class="ms-3">
											<h6 class="mb-0 text-dark">Banner Limit</h6>
											<div class="text-dark">You cannot add more banners. Maximum limit of 3 reached!</div>
										</div>
									</div>
								</div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
                // Query to get all banners from the `banner-content` table
                $query = "SELECT `id`, `banner_title`, `banner_desc`, `image_content`, `status`, `updated_at` FROM `banner-content` ORDER BY `id` ASC";
                $result = $conn->query($query);

                date_default_timezone_set('Asia/Jakarta'); // e.g., 'America/New_York'
                function timeAgo($datetime)
                {
                    // Ensure the datetime is in the correct format
                    $timeAgo = strtotime($datetime);

                    // Check if the conversion was successful
                    if ($timeAgo === false) {
                        return "Invalid date";
                    }

                    $currentTime = time();
                    $timeDifference = $currentTime - $timeAgo;

                    $seconds = $timeDifference;
                    $minutes = round($seconds / 60);
                    $hours = round($seconds / 3600);
                    $days = round($seconds / 86400);
                    $weeks = round($seconds / 604800);
                    $months = round($seconds / 2629440);
                    $years = round($seconds / 31553280);

                    if ($seconds <= 60) {
                        return "just now";
                    } elseif ($minutes <= 60) {
                        return ($minutes == 1) ? "one minute ago" : "$minutes minutes ago";
                    } elseif ($hours <= 24) {
                        return ($hours == 1) ? "an hour ago" : "$hours hours ago";
                    } elseif ($days <= 7) {
                        return ($days == 1) ? "yesterday" : "$days days ago";
                    } elseif ($weeks <= 4.3) {
                        return ($weeks == 1) ? "a week ago" : "$weeks weeks ago";
                    } elseif ($months <= 12) {
                        return ($months == 1) ? "a month ago" : "$months months ago";
                    } else {
                        return ($years == 1) ? "one year ago" : "$years years ago";
                    }
                }

                if ($result->num_rows > 0): ?>
                    <div class="card-group shadow">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="card border-end shadow-none">
                                <img src="../uploads/<?php echo htmlspecialchars($row['image_content']); ?>" class="card-img-top" alt="Banner Image">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo htmlspecialchars(!empty($row['banner_title']) ? $row['banner_title'] : 'No Title'); ?>
                                    </h5>
                                    <p class="card-text">
                                        <?php echo htmlspecialchars(!empty($row['banner_desc']) ? $row['banner_desc'] : 'No Description'); ?>
                                    </p>

                                    <small>Status:</small>
                                    <small class="<?php echo ($row['status'] == 'published') ? 'text-success' : 'text-warning'; ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </small>
                                </div>
                                <div class="card-footer bg-white">
                                    <small class="text-muted">Last Updated <?php echo timeAgo($row['updated_at']); ?></small>
                                </div>
                                <div class="card-footer bg-white">
                                    <a href="edit-banner.php?id=<?php echo $row['id']; ?>" class="card-link">Edit Banner</a>
                                    <a href="javascript:;" class="card-link text-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteBannerModal" data-banner-id="<?php echo $row['id']; ?>">Delete Banner</a>

                                    <!-- Delete Confirmation Modal -->
                                    <div class="modal fade" id="deleteBannerModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirm Deletion</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="deleteAlert" class="alert alert-danger border-0 bg-danger alert-dismissible fade hide d-none">
                                                        <div class="d-flex align-items-center">
                                                            <div class="font-35 text-white"><i class="bx bxs-message-square-x"></i></div>
                                                            <div class="ms-3">
                                                                <h6 class="mb-0 text-white">Unable to Delete</h6>
                                                                <div class="text-white">Cannot delete the last remaining banner.</div>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>
                                                    Are you sure you want to delete this banner? This action cannot be undone.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete Banner</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p>No banners available.</p>
                <?php endif; ?>
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
    <script>
        let currentBannerId;

        // Get bannerId when the delete modal is triggered
        document.querySelectorAll('[data-bs-target="#deleteBannerModal"]').forEach(button => {
            button.addEventListener('click', function() {
                currentBannerId = this.getAttribute('data-banner-id');
            });
        });

        // Confirm delete action and check banner count
        function confirmDelete() {
            attemptDelete(currentBannerId);
        }

        function attemptDelete(bannerId) {
            // AJAX request to check banner count
            fetch('check-banner-count.php')
                .then(response => response.json())
                .then(data => {
                    if (data.banner_count > 1) {
                        // Proceed with deletion if more than one banner exists
                        window.location.href = `delete-banner.php?id=${bannerId}`;
                    } else {
                        // Show danger alert if only one banner remains
                        const deleteAlert = document.getElementById("deleteAlert");
                        deleteAlert.classList.remove("d-none");
                        deleteAlert.classList.add("show");
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
    <?php include '../include/bootstrap-script.php'; ?>
</body>

</html>