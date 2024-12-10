<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../include/connection/connection.php';

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit;
}

$movie_id = $_GET['id'];

// Fetch movie details by ID
// Fetch movie details by ID
$sql = "SELECT poster_image, title, producer, director, writer, genre, duration, description FROM movies WHERE id = ?";
$stmt = $conn->prepare($sql);

// Check if the prepare() failed
if ($stmt === false) {
    // If prepare fails, output the error
    echo "Error preparing SQL query: " . $conn->error;
    exit;
}

$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $movie = $result->fetch_assoc();
} else {
    echo "Movie not found.";
    exit;
}



include '../include/head.php';
?>

<body>
    <div class="wrapper">
        <!-- Sidebar based on user role -->
        <?php
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            include '../include/admin/sidebar.php';
            include '../include/admin/header.php';
        } else {
            include '../include/user/sidebar.php';
            include '../include/user/header.php';
        }
        ?>

        <div class="page-wrapper">
            <div class="page-content">
                <div class="card">
                    <div class="row g-0">
                        <!-- Movie Image Carousel -->
                        <div class="col-md-4 border-end">
                            <div class="image-zoom-section">
                                <div class="product-gallery owl-carousel owl-theme p-3" data-slider-id="1">
                                    <div class="item">
                                        <img src="../uploads/<?php echo $movie['poster_image']; ?>" class="img-fluid" alt="Poster for <?php echo $movie['title']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Movie Information -->
                        <div class="col-md-8">
                            <div class="card-body">
                                <h2 class="card-title"><?php echo $movie['title']; ?></h2>
                                <p class="text-muted">
                                    <?php
                                    // Limit description to 50 characters
                                    echo ($movie['description']);
                                    ?>
                                </p>
                                <dl class="row">
                                    <dt class="col-sm-3">Genre</dt>
                                    <dd class="col-sm-9"><?php echo $movie['genre']; ?></dd>

                                    <dt class="col-sm-3">Duration</dt>
                                    <dd class="col-sm-9"><?php echo $movie['duration']; ?> mins</dd>

                                    <dt class="col-sm-3">Producer</dt>
                                    <dd class="col-sm-9"><?php echo $movie['producer']; ?></dd>

                                    <dt class="col-sm-3">Director</dt>
                                    <dd class="col-sm-9"><?php echo $movie['director']; ?></dd>

                                    <dt class="col-sm-3">Writer</dt>
                                    <dd class="col-sm-9"><?php echo $movie['writer']; ?></dd>

                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Tabbed Section -->
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-primary mb-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#description" role="tab">
                                    <i class="bx bx-comment-detail font-18 me-1"></i> Description
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#genres" role="tab">
                                    <i class="bx bx-bookmark-alt font-18 me-1"></i> Genres
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content pt-3">
                            <div class="tab-pane fade show active" id="description" role="tabpanel">
                                <p><?php echo $movie['description']; ?></p>
                            </div>
                            <div class="tab-pane fade" id="genres" role="tabpanel">
                                <p><?php echo $movie['genre']; ?></p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php include '../include/bootstrap-script.php'; ?>
    <script src="path-to-owl-carousel-js/owl.carousel.min.js"></script>
    <script>
        // Initialize Owl Carousel for movie images
        $(document).ready(function() {
            $(".product-gallery").owlCarousel({
                items: 1,
                loop: true,
                nav: true,
                dots: true
            });
        });
    </script>
</body>

</html>