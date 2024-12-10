<!-- HTML Head -->
<?php include '../include/head.php'; ?>
<?php include '../include/connection/session.php';

if ($_SESSION['role'] != 'admin') {
    // If the user is an admin, redirect to the dashboard page
    header('Location: ../homepage/');
    exit();
}

?>

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
                <div class="breadcrumb-title pe-3">Dashboard</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="../dashboard/"><i class="bx bx-home-alt"></i></a>
                            <li class="breadcrumb-item active" aria-current="page">Insert Movie</li>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
            <?php
            // Include database connection
            include '../include/connection/connection.php';

            // Get the movie ID from the URL
            $movie_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

            // Fetch the movie details from the database
            $sql = "SELECT * FROM movies WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $movie_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $movie = $result->fetch_assoc();
            $stmt->close();
            ?>

            <div class="row">
                <div class="col-xl-9 mx-auto">
                    <h6 class="mb-0 text-uppercase">Edit Movie</h6>
                    <hr>
                    <div class="card">
                        <div class="card-body">
                            <form action="edit-logic.php" method="post" enctype="multipart/form-data" id="movieForm" onsubmit="return validateForm()">
                                <input type="hidden" name="movie_id" value="<?php echo htmlspecialchars($movie['id']); ?>">

                                <div class="mb-3">
                                    <label class="form-label">Movie Title</label>
                                    <input type="text" class="form-control" name="movie_title" placeholder="Movie Name" value="<?php echo htmlspecialchars($movie['title']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" placeholder="Movie Description" required><?php echo htmlspecialchars($movie['description']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Date:</label>
                                    <input type="date" class="form-control" name="date" value="<?php echo htmlspecialchars($movie['release_date']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Current Thumbnail</label>
                                    <img class="d-block w-50" src="../uploads/<?php echo htmlspecialchars($movie['poster_image']); ?>" alt="Movie Thumbnail" width="100">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Thumbnail:</label>
                                    <input type="file" class="form-control" name="thumbnail">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Time Start:</label>
                                    <input type="time" class="form-control" name="time_start" value="<?php echo htmlspecialchars($movie['time_start']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Time End:</label>
                                    <input type="time" class="form-control" name="time_end" value="<?php echo htmlspecialchars($movie['time_end']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Trailer Link: (Optional)</label>
                                    <input type="url" class="form-control" name="trailer_link" placeholder="https://example.com/trailer" value="<?php echo htmlspecialchars($movie['trailer_link'] ?? ''); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Genre:</label>
                                    <input type="text" class="form-control" name="genre" data-role="tagsinput" value="<?php echo htmlspecialchars($movie['genre']); ?>" placeholder="Add Genres">
                                </div>
                                <button type="button" class="btn btn-primary" onclick="validateForm()">Submit</button>
                            </form>

                            <!-- Warning Modal -->
                            <div class="modal fade" id="warningModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-danger" id="modalTitle">Missing Fields</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" id="modalBody">
                                            Please fill in all required fields.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" id="secondaryButton" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary" id="confirmButton" style="display: none;" onclick="submitForm()">Update Movie</button>
                                        </div>
                                    </div>
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
    <p class="mb-0">Budi Luhur © 2021. All right reserved.</p>
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
    function validateForm() {
        const form = document.getElementById('movieForm');
        const movieTitle = form.elements['movie_title'];
        const description = form.elements['description'];
        const date = form.elements['date'];
        const timeStart = form.elements['time_start'];
        const timeEnd = form.elements['time_end'];

        let emptyFields = [];

        if (movieTitle.value.trim() === '') emptyFields.push('Movie Title');
        if (description.value.trim() === '') emptyFields.push('Description');
        if (date.value.trim() === '') emptyFields.push('Date');
        if (timeStart.value.trim() === '') emptyFields.push('Time Start');
        if (timeEnd.value.trim() === '') emptyFields.push('Time End');

        const modalTitle = document.getElementById('modalTitle');
        const modalBody = document.getElementById('modalBody');
        const confirmButton = document.getElementById('confirmButton');
        const secondaryButton = document.getElementById('secondaryButton');

        // Show warning if fields are empty
        if (emptyFields.length > 0) {
            modalTitle.textContent = "Incomplete Data!";
            modalBody.textContent = "Please fill in the following fields: " + emptyFields.join(', ');
            confirmButton.style.display = "none"; // Hide confirmation button
            secondaryButton.classList.add("btn-danger");
            secondaryButton.classList.remove("btn-secondary");
            secondaryButton.textContent = "I Understand";
        } else {
            // Show confirmation if all fields are filled
            modalTitle.textContent = "Add Movie?";
            modalBody.textContent = "Are you sure you want to add this movie? Don't forget to double check the movie data";
            confirmButton.style.display = "inline-block"; // Show confirmation button
            modalTitle.classList.remove("text-danger");
            secondaryButton.classList.add("btn-secondary");
            secondaryButton.classList.remove("btn-danger");
            secondaryButton.textContent = "Return";
        }

        const warningModal = new bootstrap.Modal(document.getElementById('warningModal'));
        warningModal.show();

        return false; // Prevent form from submitting directly
    }

    function submitForm() {
        document.getElementById('movieForm').submit(); // Submit form after confirmation
    }
</script>
<?php include '../include/bootstrap-script.php'; ?>
</body>

</html>