<!-- HTML Head -->
<?php include '../include/head.php'; ?>
<?php include '../include/connection/session.php';

if ($_SESSION['role'] != 'admin') {
    // If the user is an admin, redirect to the dashboard page
    header('Location: ../order/');
    exit();
}

?>

<!--wrapper-->
<div class="wrapper">

    <div class="page-content">
        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h6 class="mb-0 text-uppercase">Input Banner</h6>
                <hr>
                <div class="card">
                    <div class="card-body">
                        <form action="logic.php" method="post" enctype="multipart/form-data" id="bannerForm" onsubmit="return validateForm()">
                            <div class="mb-3">
                                <label class="form-label">Banner Title</label>
                                <input type="text" class="form-control" name="banner_title" placeholder="Banner Title">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="banner_desc" placeholder="Banner Description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Banner Image</label>
                                <div class="input-group has-validation mb-3">
                                    <input type="file" class="form-control" id="imageInput" name="image_content" aria-describedby="inputGroupPrepend3 validationServerUsernameFeedback">
                                    <div id="imageInputFeedback" class="invalid-feedback">Please select an image for the banner.</div>
                                </div>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="status" value="published">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Publish Now</label>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>


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
</div>
<!--end wrapper-->

<script>
    function validateForm() {
        const fileInput = document.getElementById('imageInput');
        const feedback = document.getElementById('imageInputFeedback');

        // Reset the input style and feedback
        fileInput.classList.remove('is-invalid');
        feedback.style.display = 'none';

        // Check if the file input is empty
        if (fileInput.files.length === 0) {
            fileInput.classList.add('is-invalid'); // Add invalid class
            feedback.style.display = 'block'; // Show feedback
            return false; // Prevent form submission
        }

        return true; // Allow form submission
    }
</script>
<?php include '../include/bootstrap-script.php'; ?>
</body>

</html>