<!-- HTML Head -->
<?php include '../include/head.php'; ?>
<?php include '../include/connection/session.php';

if ($_SESSION['role'] != 'admin') {
    // Redirect non-admins to the order page
    header('Location: ../order/');
    exit();
}

include '../include/connection/connection.php';

// Retrieve banner details based on the banner ID
$banner_id = $_GET['id'];
$query = "SELECT banner_title, banner_desc, image_content, status FROM `banner-content` WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $banner_id);
$stmt->execute();
$result = $stmt->get_result();
$banner = $result->fetch_assoc();

?>

<!-- Wrapper -->
<div class="wrapper">
    <div class="page-content">
        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h6 class="mb-0 text-uppercase">Edit Banner</h6>
                <hr>
                <div class="card">
                    <div class="card-body">
                        <form action="edit-logic.php" method="post" enctype="multipart/form-data" id="bannerForm" onsubmit="return validateForm()">
                            <input type="hidden" name="banner_id" value="<?php echo htmlspecialchars($banner_id); ?>">

                            <div class="mb-3">
                                <label class="form-label">Banner Title</label>
                                <input type="text" class="form-control" name="banner_title" value="<?php echo htmlspecialchars($banner['banner_title']); ?>" placeholder="Banner Title">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="banner_desc" placeholder="Banner Description"><?php echo htmlspecialchars($banner['banner_desc']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Current Image</label>
                                <?php if ($banner['image_content']): ?>
                                    <img class="d-block w-50" src="../uploads/<?php echo htmlspecialchars($banner['image_content']); ?>" alt="Banner Image" width="100">
                                <?php else: ?>
                                    <p>No image uploaded</p>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">New Banner Image</label>
                                <div class="input-group has-validation mb-3">
                                    <input type="file" class="form-control" id="imageInput" name="image_content" aria-describedby="inputGroupPrepend3 validationServerUsernameFeedback">
                                    <div id="imageInputFeedback" class="invalid-feedback">Please select an image for the banner.</div>
                                </div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="status" value="published" <?php echo ($banner['status'] === 'published') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Publish Now</label>
                            </div>

                            <button type="submit" class="btn btn-primary" name="edit_banner">Submit</button>
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
