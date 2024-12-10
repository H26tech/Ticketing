<?php
session_start();
include '../include/connection/connection.php';
?>

<?php include '../include/head.php'; ?>


<body>

    <div class="wrapper">
        <!--sidebar wrapper -->
        <?php
        // Include the sidebar based on the user role
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            include '../include/admin/sidebar.php';
        } else {
            include '../include/user/sidebar.php';
        }
        ?>
        <!--end sidebar wrapper -->
        <!--start header -->
        <?php
        // Include the header based on the user role
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            include '../include/admin/header.php';
        } else {
            include '../include/user/header.php';
        }
        ?> <!--end header -->
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <div class="row justify-content-center mb-3">
                    <div class="col-lg-8 col-xl-8 mx-center"> <!-- Adjust the column size and centering -->
                        <?php
                        // Query to get all banner content
                        $query = "SELECT `id`, `banner_title`, `banner_desc`, `image_content` 
                                FROM `banner-content` 
                                WHERE `status` = 'published' 
                                ORDER BY `id` ASC";
                        $result = $conn->query($query);

                        // Initialize variables for carousel indicators and items
                        $indicators = '';
                        $carouselItems = '';
                        $isActive = true; // Variable to set the first item as active

                        if ($result->num_rows > 0) {
                            $slideIndex = 0;
                            while ($row = $result->fetch_assoc()) {
                                // Carousel indicators
                                $indicators .= '<li data-bs-target="#carouselExampleCaptions" data-bs-slide-to="' . $slideIndex . '"';
                                $indicators .= $isActive ? ' class="active"></li>' : '></li>';

                                // Carousel items
                                $carouselItems .= '<div class="carousel-item' . ($isActive ? ' active' : '') . '">';
                                $carouselItems .= '<img src="../uploads/' . $row['image_content'] . '" class="d-block w-100" alt="' . htmlspecialchars($row['banner_title']) . '">';
                                $carouselItems .= '<div class="carousel-caption d-none d-md-block">';
                                $carouselItems .= '<h5 class="text-light">' . htmlspecialchars($row['banner_title']) . '</h5>';
                                $carouselItems .= '<p>' . htmlspecialchars($row['banner_desc']) . '</p>';
                                $carouselItems .= '</div></div>';

                                $isActive = false; // Set to false after the first iteration
                                $slideIndex++;
                            }
                        } else {
                            echo '<p>No banners available.</p>'; // Message if no banners are found
                        }
                        ?>

                        <div class="card">
                            <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                                <ol class="carousel-indicators">
                                    <?php echo $indicators; ?>
                                </ol>
                                <div class="carousel-inner">
                                    <?php echo $carouselItems; ?>
                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                <hr>
                <h2 class="font-weight-light text-center py-3">Our Movies</h2>
                <div class="row row-cols-2 row-cols-xl-3">

                    <?php
                    $sql = "SELECT id, poster_image, genre, duration, title FROM movies"; // Pastikan Anda juga mengambil id film
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $movie_id = $row['id']; // Ambil id film
                    ?>
                            <div class="col">
                                <!-- Tambahkan link ke halaman deskripsi dengan parameter ID film -->
                                <a href="movie_description.php?id=<?php echo $movie_id; ?>" class="text-decoration-none">
                                    <div class="card">
                                        <img src="../uploads/<?php echo $row['poster_image']; ?>" class="card-img-top" alt="Poster for <?php echo $row['title']; ?>">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $row['title']; ?></h5>
                                            <p class="card-text">
                                                Genre: <?php echo $row['genre']; ?><br>
                                                Duration: <?php echo $row['duration']; ?> mins
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                    <?php
                        }
                    } else {
                        echo "No records found";
                    }
                    ?>

                </div>

                <hr>
                <div class="container py-2">
                    <h2 class="font-weight-light text-center py-3">How Can I Watch?</h2>
                    <!-- timeline item 1 -->
                    <div class="row g-0">
                        <div class="col-sm">
                            <!--spacer-->
                        </div>
                        <!-- timeline item 1 center dot -->
                        <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
                            <div class="row h-50">
                                <div class="col">&nbsp;</div>
                                <div class="col">&nbsp;</div>
                            </div>
                            <h5 class="m-2">
                                <span class="badge rounded-pill bg-dark border">&nbsp;</span>
                            </h5>
                            <div class="row h-50">
                                <div class="col border-end">&nbsp;</div>
                                <div class="col">&nbsp;</div>
                            </div>
                        </div>
                        <!-- timeline item 1 event content -->
                        <div class="col-sm py-2">
                            <div class="card radius-15">
                                <div class="card-body">
                                    <div class="float-end text-muted small"></div>
                                    <h4 class="card-title text-primary">Step 1, Look Around</h4>
                                    <p class="card-text">Welcome to Bluvocation Fest Tickets, feel free to explore and take a look to our movie posters.
                                        <br><br> Don't forget to personalize your <a href="../dashboard/profile.php">profile</a> the way you please! of course with valid information.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/row-->
                    <!-- timeline item 2 -->
                    <div class="row g-0">
                        <div class="col-sm py-2">
                            <div class="card border-primary shadow radius-15">
                                <div class="card-body">
                                    <div class="float-end text-primary small"></div>
                                    <h4 class="card-title text-primary">Step 2, Get Your Ticket!</h4>
                                    <p class="card-text">
                                        Don't let the others get your ticket, choose where to seat now!
                                        Order your seat <a id="orderLink" href="../order/">here!</a>
                                        <br><br>
                                        <a id="orderLink2" href="../order/">YOU CAN ALSO CLICK THIS TO ORDER YOUR TICKET NOW!</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
                            <div class="row h-50">
                                <div class="col border-end">&nbsp;</div>
                                <div class="col">&nbsp;</div>
                            </div>
                            <h5 class="m-2">
                                <span class="badge rounded-pill bg-dark">&nbsp;</span>
                            </h5>
                            <div class="row h-50">
                                <div class="col border-end">&nbsp;</div>
                                <div class="col">&nbsp;</div>
                            </div>
                        </div>
                        <div class="col-sm">
                            <!--spacer-->
                        </div>
                    </div>
                    <!--/row-->
                    <!-- timeline item 3 -->
                    <div class="row g-0">
                        <div class="col-sm">
                            <!--spacer-->
                        </div>
                        <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
                            <div class="row h-50">
                                <div class="col border-end">&nbsp;</div>
                                <div class="col">&nbsp;</div>
                            </div>
                            <h5 class="m-2">
                                <span class="badge rounded-pill bg-dark border">&nbsp;</span>
                            </h5>
                            <div class="row h-50">
                                <div class="col border-end">&nbsp;</div>
                                <div class="col">&nbsp;</div>
                            </div>
                        </div>
                        <div class="col-sm py-2">
                            <div class="card radius-15">
                                <div class="card-body">
                                    <div class="float-end text-muted small"></div>
                                    <h4 class="card-title text-primary">Step 3, Pay Your Tickets</h4>
                                    <p>After choosing the comfort seat to watch your movie, don't forget to pay okay? We'll gonna be sad if you don't <i class="lni lni-emoji-sad"></i></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/row-->
                    <!-- timeline item 4 -->
                    <div class="row g-0">
                        <div class="col-sm py-2">
                            <div class="card radius-15">
                                <div class="card-body">
                                    <div class="float-end text-muted small"></div>
                                    <h4 class="card-title text-primary">Step 4, Yay Let's Go!</h4>
                                    <p class="card-text">
                                        If you already pay then you just need to wait a bit for your payment to be approved.
                                        If your payment is approved, you can see your invoice <a id="invoiceLink" href="../order/history.php">HERE!</a> or we will send it through your email!
                                        <br><br>That's why you should set your profile with valid information, so we could recognize you correctly <i class="lni lni-emoji-happy"></i>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
                            <div class="row h-50">
                                <div class="col border-end">&nbsp;</div>
                                <div class="col">&nbsp;</div>
                            </div>
                            <h5 class="m-2">
                                <span class="badge rounded-pill bg-dark border">&nbsp;</span>
                            </h5>
                            <div class="row h-50">
                                <div class="col">&nbsp;</div>
                                <div class="col">&nbsp;</div>
                            </div>
                        </div>
                        <div class="col-sm">
                            <!--spacer-->
                        </div>
                    </div>
                    <!--/row-->
                </div>
                <hr>
                <h2 class="font-weight-light text-center py-3">BFF's Social Media</h2>
                <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2 row-cols-xl-2">
                    <?php
                    // Query to get the latest two Instagram post links
                    $query = "SELECT `post_link` FROM `instagram-post` ORDER BY id DESC LIMIT 2";
                    $result = $conn->query($query);

                    // Initialize an empty array to store post links
                    $postLinks = [];

                    // Fetch each result row and add the link to the array
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $postLinks[] = $row['post_link'];
                        }
                    }
                    ?>
                    <?php if (!empty($postLinks)): ?>
                        <?php foreach ($postLinks as $postLink): ?>
                            <div class="col">
                                <div class="card">
                                    <blockquote class="instagram-media" data-instgrm-permalink="<?php echo $postLink; ?>" data-instgrm-version="13"></blockquote>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No Instagram posts available.</p>
                    <?php endif; ?>
                </div>

                <!-- contacts list -->
                <hr>
                <h1 class="text-center">Contact Us</h1>
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4 justify-content-center">
                    <?php
                    // Fetch details specifically for Devara and Rafly
                    $query = "SELECT full_name, phone_number, profile_image FROM users WHERE username = 'Devara' OR username = 'Rafly'";
                    $result = $conn->query($query);

                    if ($result && $result->num_rows > 0) {
                        while ($user = $result->fetch_assoc()) {
                    ?>
                            <div class="col">
                                <div class="card radius-15">
                                    <div class="card-body text-center">
                                        <div class="p-4 border radius-15">
                                            <h5 class="mb-0 mt-5"><?php echo htmlspecialchars($user['full_name']); ?></h5>
                                            <p class="mb-3">Phone: +<?php echo htmlspecialchars($user['phone_number']); ?></p>

                                            <div class="d-grid">
                                                <a href="https://wa.me/<?php echo htmlspecialchars($user['phone_number']); ?>" class="btn btn-outline-primary radius-15">Contact Me</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<p class="text-center">No contact information available.</p>';
                    }
                    ?>
                </div>


                <!-- sponsor list -->
                <hr>
                <h1 class="text-center">Our Sponsor</h1>
                <div class="container my-4">
                    <div class="row row-cols-1 row-cols-md-4 g-4 text-center">
                        <?php
                        include '../include/connection/connection.php';

                        // Query untuk mengambil data sponsor dari database
                        $query = "SELECT nama_sponsor, foto_logo FROM sponsor LIMIT 6";
                        $result = mysqli_query($conn, $query);

                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<div class="col">';
                                echo '<div class="card shadow-sm">';
                                echo '<div class="card-body">';

                                // Menampilkan logo sponsor
                                echo '<img src="../uploads/' . htmlspecialchars($row['foto_logo']) . '" class="card-img-top" alt="' . htmlspecialchars($row['nama_sponsor']) . '" style="height: 100px; width: auto;">';

                                echo '</div>'; // end card-body
                                echo '</div>'; // end card
                                echo '</div>'; // end col
                            }
                        } else {
                            // Pesan jika tidak ada data sponsor
                            echo '<p class="text-center">No sponsors available at this time.</p>';
                        }
                        ?>                    </div>
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
        <p class="mb-0">Budi Luhur � 2024. All right reserved.</p>
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

    <div class="modal fade" id="adminRestrictionModal" tabindex="-1" aria-labelledby="adminRestrictionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminRestrictionModalLabel">Access Restricted</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Admins cannot perform this action. Please switch to a user account to proceed.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set the user's role in JavaScript based on the PHP session variable
        let userRole = <?php echo json_encode($_SESSION['role'] ?? ''); ?>;

        document.querySelectorAll("#orderLink, #orderLink2, #invoiceLink").forEach(link => {
            link.addEventListener("click", function(event) {
                if (userRole === 'admin') {
                    event.preventDefault(); // Prevents the link from navigating
                    var modal = new bootstrap.Modal(document.getElementById('adminRestrictionModal'), {});
                    modal.show();
                }
            });
        });
    </script>

    <script async src="//www.instagram.com/embed.js"></script>
    <?php include '../include/bootstrap-script.php'; ?>
</body>

</html>