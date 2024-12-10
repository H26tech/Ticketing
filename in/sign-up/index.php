<!-- HTML Head -->
<?php include '../include/head.php'; ?>

<body class="bg-forgot">
    <!-- wrapper -->
    <div class="wrapper">
        <div class="d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2">
                    <div class="col mx-auto">
                        <div class="my-4 text-center">
                            <img src="../assets/images/BFF-logo.png" width="180" alt="">
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <h3 class="">Sign Up</h3>
                                        <p>Already have an account? <a href="../login/">Sign in here</a>
                                        </p>
                                    </div>
                                    <div class="form-body">
                                        <form method="post" class="row g-3 needs-validation" action="logic.php" novalidate>
                                            <div class="col-12">
                                                <label for="inputFullName" class="form-label">Full Name</label>
                                                <input type="text" name="full_name" class="form-control" id="inputFullName" placeholder="John Doe" required>
                                                <div id="fullNameFeedback" class="feedback-message"></div>
                                            </div>

                                            <div class="col-12">
                                                <label for="inputPhoneNumber" class="form-label">Phone Number</label>
                                                <input type="text" class="form-control" id="inputPhoneNumber" name="phone_number" placeholder="01234567890" required>
                                                <div class="valid-feedback">Valid phone number!</div>
                                                <div class="invalid-feedback">Please provide a valid phone number (numbers only).</div>
                                            </div>

                                            <div class="col-12">
                                                <label for="inputEmailAddress" class="form-label">Email Address</label>
                                                <input type="email" name="email" class="form-control" id="inputEmailAddress" placeholder="example@user.com" required>
                                                <div id="emailFeedback" class="feedback-message"></div>
                                            </div>

                                            <div class="col-12">
                                                <label for="inputChoosePassword" class="form-label">Password</label>
                                                    <input type="password" name="password" class="form-control" id="inputChoosePassword" placeholder="Enter Password" minlength="6" required>
                                                    <div id="passwordFeedback" class="feedback-message"></div>
                                            </div>
                                            <div class="col-12">
                                                <label for="inputRepeatPassword" class="form-label">Repeat Password</label>
                                                    <input type="password" name="repeat_password" class="form-control" id="inputRepeatPassword" placeholder="Confirm Password" minlength="6" required>
                                                    <div id="repeatPasswordFeedback" class="feedback-message"></div>
                                            </div>


                                            <div class="col-12" style="padding-left: 4px;">
                                                <a href="javascript:;" class="bg-transparent show-password-toggle">Show Password <i class="bx bx-hide"></i></a>
                                            </div>

                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary">Sign up</button>
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
            const form = document.querySelector("form");

            form.addEventListener("submit", function(event) {
                let isValid = true; // Track overall validity

                // Check validity for each input field
                const inputs = form.querySelectorAll("input");
                inputs.forEach(input => {
                    if (!input.checkValidity()) {
                        isValid = false; // Mark as invalid if any input is invalid
                        input.classList.add("is-invalid");
                        input.classList.remove("is-valid");
                        const feedback = input.nextElementSibling;
                        if (input.value.length === 0) {
                            feedback.textContent = `${input.placeholder} is required.`; // Provide specific feedback
                            feedback.classList.add("text-danger");
                        } else {
                            feedback.textContent = ""; // Clear feedback if invalid but not empty
                        }
                    } else {
                        input.classList.remove("is-invalid");
                        input.classList.add("is-valid");
                    }
                });

                if (!isValid) {
                    event.preventDefault(); // Prevent form submission if any field is invalid
                }

                form.classList.add("was-validated");
            });

            // Full name validation
            const fullNameInput = document.getElementById("inputFullName");
            const fullNameFeedback = document.getElementById("fullNameFeedback");
            fullNameInput.addEventListener("input", function() {
                if (fullNameInput.value.length === 0) {
                    fullNameInput.classList.remove("is-valid");
                    fullNameInput.classList.add("is-invalid");
                    fullNameFeedback.textContent = "Full name is required.";
                    fullNameFeedback.classList.add("text-danger");
                } else {
                    checkExistence('name', fullNameInput.value, fullNameFeedback);
                }
            });

            // Email validation
            const emailInput = document.getElementById("inputEmailAddress");
            const emailFeedback = document.getElementById("emailFeedback");
            emailInput.addEventListener("input", function() {
                if (emailInput.value.length === 0) {
                    emailInput.classList.remove("is-valid");
                    emailInput.classList.add("is-invalid");
                    emailFeedback.textContent = "Email is required.";
                    emailFeedback.classList.add("text-danger");
                } else if (emailInput.checkValidity()) {
                    checkExistence('email', emailInput.value, emailFeedback);
                } else {
                    emailInput.classList.remove("is-valid");
                    emailInput.classList.add("is-invalid");
                    emailFeedback.textContent = "Please enter a valid email address.";
                    emailFeedback.classList.add("text-danger");
                }
            });

            // Phone number validation
            const phoneInput = document.getElementById("inputPhoneNumber");
            const phoneFeedback = document.getElementById("phoneFeedback");
            phoneInput.addEventListener("input", function() {
                const phoneRegex = /^[0-9]*$/;
                if (phoneInput.value.length === 0) {
                    phoneInput.classList.remove("is-valid");
                    phoneInput.classList.add("is-invalid");
                    phoneFeedback.textContent = "Phone number is required.";
                    phoneFeedback.classList.add("text-danger");
                } else if (phoneRegex.test(phoneInput.value)) {
                    phoneInput.classList.remove("is-invalid");
                    phoneInput.classList.add("is-valid");
                    phoneFeedback.textContent = "Looks good!";
                    phoneFeedback.classList.remove("text-danger");
                    phoneFeedback.classList.add("text-success");
                } else {
                    phoneInput.classList.remove("is-valid");
                    phoneInput.classList.add("is-invalid");
                    phoneFeedback.textContent = "Phone number should contain only numbers.";
                    phoneFeedback.classList.add("text-danger");
                }
            });

            // Password validation
            const passwordInput = document.getElementById("inputChoosePassword");
            const repeatPasswordInput = document.getElementById("inputRepeatPassword");
            const passwordFeedback = document.getElementById("passwordFeedback");
            const repeatPasswordFeedback = document.getElementById("repeatPasswordFeedback");

            passwordInput.addEventListener("input", function() {
                if (passwordInput.value.length < 6) {
                    passwordInput.classList.remove("is-valid");
                    passwordInput.classList.add("is-invalid");
                    passwordFeedback.textContent = "Password must be at least 6 characters long.";
                    passwordFeedback.classList.add("text-danger");
                } else {
                    passwordInput.classList.remove("is-invalid");
                    passwordInput.classList.add("is-valid");
                    passwordFeedback.textContent = "Looks good!";
                    passwordFeedback.classList.remove("text-danger");
                    passwordFeedback.classList.add("text-success");
                }
            });

            repeatPasswordInput.addEventListener("input", function() {
                if (repeatPasswordInput.value === passwordInput.value && repeatPasswordInput.value.length >= 6) {
                    repeatPasswordInput.classList.remove("is-invalid");
                    repeatPasswordInput.classList.add("is-valid");
                    repeatPasswordFeedback.textContent = "Passwords match!";
                    repeatPasswordFeedback.classList.remove("text-danger");
                    repeatPasswordFeedback.classList.add("text-success");
                } else {
                    repeatPasswordInput.classList.remove("is-valid");
                    repeatPasswordInput.classList.add("is-invalid");
                    repeatPasswordFeedback.textContent = "Passwords do not match.";
                    repeatPasswordFeedback.classList.add("text-danger");
                }
            });

            // AJAX function to check existence
            function checkExistence(type, value, feedbackElement) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "validate.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        const response = xhr.responseText;
                        if (response === "exists") {
                            feedbackElement.textContent = type === 'name' ? "Name already exists." : "Email already registered.";
                            feedbackElement.classList.add("text-danger");
                            feedbackElement.classList.remove("text-success");
                            feedbackElement.previousElementSibling.classList.remove("is-valid");
                            feedbackElement.previousElementSibling.classList.add("is-invalid");
                        } else {
                            feedbackElement.textContent = "Looks good!";
                            feedbackElement.classList.add("text-success");
                            feedbackElement.classList.remove("text-danger");
                            feedbackElement.previousElementSibling.classList.remove("is-invalid");
                            feedbackElement.previousElementSibling.classList.add("is-valid");
                        }
                    }
                };
                xhr.send(`type=${type}&value=${encodeURIComponent(value)}`);
            }

            // Show/Hide password toggle
            const showPasswordToggle = document.querySelector(".show-password-toggle");
            showPasswordToggle.addEventListener("click", function() {
                const passwordField = document.getElementById("inputChoosePassword");
                const repeatPasswordField = document.getElementById("inputRepeatPassword");
                const type = passwordField.type === "password" ? "text" : "password";
                passwordField.type = type;
                repeatPasswordField.type = type;
                showPasswordToggle.querySelector("i").classList.toggle("bx-hide");
                showPasswordToggle.querySelector("i").classList.toggle("bx-show");
            });
        });
    </script>

    <?php include '../include/bootstrap-script.php'; ?>
</body>

</html>