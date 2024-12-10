<!-- HTML Head -->
<?php include '../include/head.php'; ?>

<body class="bg-forgot">
    <!-- wrapper -->
    <div class="wrapper">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        <div class="mb-4 text-center">
                            <img src="../assets/images/BFF-logo.png" width="180" alt="">
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <?php
                                if (isset($_GET['error'])) {
                                    $error = $_GET['error'];
                                    if ($error == 'incorrectpassword') {
                                        $message = "Incorrect password.";
                                    } elseif ($error == 'nouser') {
                                        $message = "No account found with that email or name.";
                                    }
                                    echo '<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
									<div class="d-flex align-items-center">
										<div class="font-35 text-white"><i class="bx bxs-message-square-x"></i>
										</div>
										<div class="ms-3">
											<h6 class="mb-0 text-white">Danger Alerts</h6>
											<div class="text-white">' . $message . '</div>
										</div>
									</div>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>';
                                }
                                ?>
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <h3 class="">Sign in</h3>
                                        <p>Don't have an account yet? <a href="../sign-up/">Sign up here</a>
                                        </p>
                                    </div>
                                    <div class="form-body">
                                        <form class="row g-3" action="logic.php" method="POST" id="loginForm">
                                            <!-- Login Input (Email or Full Name) -->
                                            <div class="col-12">
                                                <label for="login_input" class="form-label">Email Address or Full Name</label>
                                                <input type="text" class="form-control" id="login_input" name="login_input" placeholder="Your Email or Full Name">
                                                <div id="loginInputFeedback" class="invalid-feedback">Please provide your email or full name.</div>
                                            </div>

                                            <!-- Password Input -->
                                            <div class="col-12">
                                                <label for="inputChoosePassword" class="form-label">Enter Password</label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password" class="form-control" id="inputChoosePassword" name="password" placeholder="Enter Password">
                                                    <div id="passwordInputFeedback" class="invalid-feedback">Please provide your password.</div>
                                                </div>
                                            </div>

                                            <!-- Forgot Password Link -->
                                            <div class="col-12 text-end">
                                                <a href="forgot_password.php">Forgot Password ?</a>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i>Sign in</button>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </div>
    </div>
    <!-- end wrapper -->

    <!-- Bootstrap JS -->
    <script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("loginForm");

    form.addEventListener("submit", function(event) {
        // Get input fields
        const loginInput = document.getElementById("login_input");
        const passwordInput = document.getElementById("inputChoosePassword");

        let isValid = true;

        // Check if login_input is empty
        if (loginInput.value.trim() === "") {
            loginInput.classList.add("is-invalid");
            isValid = false;
        } else {
            loginInput.classList.remove("is-invalid");
            loginInput.classList.add("is-valid");
        }

        // Check if password is empty
        if (passwordInput.value.trim() === "") {
            passwordInput.classList.add("is-invalid");
            isValid = false;
        } else {
            passwordInput.classList.remove("is-invalid");
            passwordInput.classList.add("is-valid");
        }

        // Prevent form submission if any fields are invalid
        if (!isValid) {
            event.preventDefault();
        }
    });
});
</script>
    <?php include '../include/bootstrap-script.php'; ?>
</body>

</html>