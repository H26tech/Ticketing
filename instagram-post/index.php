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
                    <div class="breadcrumb-title pe-3">Instagram Post</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="../dashboard/"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Instagram Post</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!--end breadcrumb-->
                <h6 class="mb-0 text-uppercase">All Post</h6>
                <hr>
                <?php
                // Query to get all posts from the `instagram-post` table
                $query = "SELECT `id`, `post_name`, `post_link`, `updated_at` FROM `instagram-post` ORDER BY `id` ASC";
                $result = $conn->query($query);

                date_default_timezone_set('Asia/Jakarta'); // Set your desired timezone
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
                    <div class="card">
                        <div class="card-body">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Post Id</th>
                                        <th scope="col">Post Name</th>
                                        <th scope="col">Post Link</th>
                                        <th scope="col">Last Updated</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <th scope="row"><?php echo htmlspecialchars($row['id']); ?></th>
                                            <td><?php echo htmlspecialchars(!empty($row['post_name']) ? $row['post_name'] : 'No Post Name'); ?></td>
                                            <td><?php echo htmlspecialchars(!empty($row['post_link']) ? $row['post_link'] : 'No Post Link'); ?></td>
                                            <td>
                                                <p class="text-muted mb-0 mt-1"><?php echo timeAgo($row['updated_at']); ?></p>
                                            </td>
                                            <td>
                                                <a href="edit-post.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <p>No posts available.</p>
                <?php endif; ?>
                <h2 class="font-weight-light text-center py-3">Post Preview</h2>
                <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-2">
                    <?php
                    $query = "SELECT `post_name`, `post_link` FROM `instagram-post` ORDER BY id ASC LIMIT 2";
                    $result = $conn->query($query);
                    
                    // Initialize arrays to store post names and links
                    $postLinks = [];
                    $postNames = [];
                    
                    // Fetch each result row and store in arrays
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $postLinks[] = $row['post_link'];
                            $postNames[] = $row['post_name'];
                        }
                    }
                    ?>
                    <?php if (!empty($postLinks) && !empty($postNames)): ?>
                        <?php foreach ($postLinks as $index => $postLink): ?>
                            <div class="col">
                                <h6 class="font-weight-light text-center py-3 mb-1"><?php echo htmlspecialchars($postNames[$index]); ?></h6>
                                <div class="card">
                                    <blockquote class="instagram-media" data-instgrm-permalink="<?php echo htmlspecialchars($postLink); ?>">
                                        <!-- Embed Instagram post content here -->
                                    </blockquote>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No Instagram posts available.</p>
                    <?php endif; ?>
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
    <script async src="//www.instagram.com/embed.js"></script>
    <?php include '../include/bootstrap-script.php'; ?>
</body>

</html>