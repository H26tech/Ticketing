<!-- HTML Head -->
<?php include '../include/head.php'; ?>
<?php include '../include/connection/session.php';

if ($_SESSION['role'] != 'admin') {
    header('Location: ../homepage/');
    exit();
}

include '../include/connection/connection.php';

// Retrieve post details based on the post ID
$post_id = $_GET['id'];
$query = "SELECT post_name, post_link FROM `instagram-post` WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

?>

<!-- Wrapper -->
<div class="wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h6 class="mb-0 text-uppercase">Edit Post</h6>
                <hr>
                <div class="card">
                    <div class="card-body">
                        <form action="edit-logic.php" method="post" enctype="multipart/form-data" id="postForm">
                            <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post_id); ?>">

                            <div class="mb-3">
                                <label class="form-label">Post Name</label>
                                <input type="text" class="form-control" name="post_name" value="<?php echo htmlspecialchars($post['post_name']); ?>" placeholder="Post Name">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Post Link</label>
                                <input type="text" class="form-control" name="post_link" value="<?php echo htmlspecialchars($post['post_link']); ?>" placeholder="Post URL/Link">
                            </div>

                            <button type="submit" class="btn btn-primary" name="edit_post">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Wrapper -->

<?php include '../include/bootstrap-script.php'; ?>
</body>
</html>
