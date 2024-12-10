<!-- forgot_password.php -->

<?php include '../include/head.php'; ?>

<body class="bg-forgot">
    <!-- wrapper -->
    <div class="wrapper">
        <div class="authentication-forgot d-flex align-items-center justify-content-center">
            <div class="card forgot-box">
                <div class="card-body">
                    <div class="p-4 rounded border">
                        <div class="text-center">
                            <img src="assets/images/icons/forgot-2.png" width="120" alt="Forgot Password Icon" />
                        </div>
                        <h4 class="mt-5 font-weight-bold">Forgot Password?</h4>
                        <p class="text-muted">Enter your registered details to reset your password</p>
                        <form method="POST" action="process_forgotpass.php">
                            <div class="my-4">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control form-control-lg" placeholder="Enter username" />
                            </div>
                            <div class="my-4">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone_number" class="form-control form-control-lg" placeholder="Enter phone number" />
                            </div>
                            <p class="text-muted text-center">OR</p>
                            <div class="my-4">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control form-control-lg" placeholder="example@user.com" />
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Reset Password</button>
                                <a href="index.php" class="btn btn-light btn-lg"><i class='bx bx-arrow-back me-1'></i>Back to Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../include/bootstrap-script.php'; ?>
</body>

</html>
