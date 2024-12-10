<!-- HTML Head -->
<?php include '../include/head.php'; ?>
<?php include '../include/connection/session.php';


?>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		<?php
		if ($_SESSION['role'] === 'admin') {
			include '../include/admin/sidebar.php';
		} else {
			include '../include/user/sidebar.php';
		}
		?>
		<!--end sidebar wrapper -->
		<!--start header -->
		<?php
		if ($_SESSION['role'] === 'admin') {
			include '../include/admin/header.php';
		} else {
			include '../include/user/header.php';
		}
		?>
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">User Profile</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">User Profile</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				<div class="container">
					<div class="main-body">
						<div class="row">
							<div class="col-lg-4">
								<div class="card">
									<div class="card-body">
										<div class="d-flex flex-column align-items-center text-center">
											<img src="<?php echo $_SESSION['profile_image']; ?>" alt="Admin" class="rounded-circle p-1 bg-primary" width="110">
											<div class="mt-3">
												<h4><?php echo $_SESSION['full_name']; ?></h4>
												<p class="text-secondary mb-1"><?php echo $_SESSION['email']; ?></p>
												<p class="text-muted font-size-sm mb-1"><?php echo $_SESSION['phone_number']; ?></p>
												<?php
												// Get the current date and time
												$currentYear = date('Y');

												// Get the updated_at date and time from the session
												$updatedAt = new DateTime($_SESSION['updated_at']);
												$updatedYear = $updatedAt->format('Y');

												// Format the time as "hour:minutes"
												$time = $updatedAt->format('H:i');

												// Format the date as "day month name"
												$date = $updatedAt->format('d F');

												// Display the year only if it's not the current year
												$formattedDate = $date;
												if ($updatedYear != $currentYear) {
													$formattedDate .= ' ' . $updatedYear;
												}
												?>

												<p class="text-muted font-size-sm">Last Updated: <br> <?php echo $time . ', ' . $formattedDate; ?></p>

												<button id="edit-btn" class="btn btn-primary" onclick="enableEditing()">Edit Profile</button>
											</div>
										</div>
										<hr class="my-4" />
									</div>
								</div>
							</div>
							<div class="col-lg-8">
								<div class="card">
									<div class="card-body">
										<h5 class="d-flex align-items-center mb-3">Your Profile</h5>
										<form id="profile-form" method="POST" action="logic.php" enctype="multipart/form-data">
											<div class="row mb-3">
												<div class="col-sm-3">
													<h6 class="mb-0">Full Name</h6>
												</div>
												<div class="col-sm-9 text-secondary">
													<input id="full_name" class="form-control mb-0" type="text" name="full_name" value="<?php echo $_SESSION['full_name']; ?>" disabled>
												</div>
											</div>
											<div class="row mb-3">
												<div class="col-sm-3">
													<h6 class="mb-0">Email</h6>
												</div>
												<div class="col-sm-9 text-secondary">
													<input id="email" class="form-control mb-0" type="email" name="email" value="<?php echo $_SESSION['email']; ?>" disabled>
												</div>
											</div>
											<div class="row mb-3">
												<div class="col-sm-3">
													<h6 class="mb-0">Username</h6>
												</div>
												<div class="col-sm-9 text-secondary">
													<input id="username" class="form-control mb-0" type="text" name="username" value="<?php echo $_SESSION['username']; ?>" disabled>
												</div>
											</div>
											<div class="row mb-3">
												<div class="col-sm-3">
													<h6 class="mb-0">Phone Number</h6>
												</div>
												<div class="col-sm-9 text-secondary">
													<input id="phone_number" class="form-control mb-0" type="text" name="phone_number" value="<?php echo $_SESSION['phone_number']; ?>" disabled>
												</div>
											</div>
											<div class="row mb-3">
												<div class="col-sm-3">
													<h6 class="mb-0">Profile Picture</h6>
												</div>
												<div class="col-sm-9 text-secondary">
													<input class="form-control" name="profile_picture" type="file" id="formFileDisabled" disabled>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-3"></div>
												<div class="col-sm-3"><button id="save-btn" class="btn btn-outline-secondary" disabled onsubmit="saveProfile()">Save Profile</button></div>
											</div>
										</form>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="card">
											<div class="card-body">
												<h5 class="d-flex align-items-center mb-3">Change Your Password</h5>
												<div class="input-group" id="show_hide_password">
													<input class="form-control mb-3" type="text" aria-label="Disabled input example" disabled="" placeholder="●●●●●●●">
												</div>
												<div class="col-sm-3 mt-2">
													<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleVerticallycenteredModal">Change Password</button>
												</div>
											</div>
										</div>

										<!-- Modal for Changing Password -->
										<div class="modal fade" id="exampleVerticallycenteredModal" tabindex="-1" aria-hidden="true" style="display: none;">
											<div class="modal-dialog modal-dialog-centered">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title">Modal title</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
														<label for="inputCurrentPassword" class="form-label">Current Password</label>
														<div class="input-group" id="show_hide_password">
															<input type="password" class="form-control border-end-0" id="inputCurrentPassword" placeholder="Enter Current Password">
															<a href="javascript:;" class="input-group-text bg-transparent">
																<i class="bx bx-hide"></i>
															</a>
														</div>

														<!-- Hidden section for New Password, shown only after validation -->
														<div id="newPasswordSection" class="mt-4" style="display: none;">
															<label for="inputNewPassword" class="form-label">New Password</label>
															<div class="input-group" id="show_hide_new_password">
																<input type="password" class="form-control border-end-0" id="inputNewPassword" placeholder="Enter New Password">
																<a href="javascript:;" class="input-group-text bg-transparent" onclick="togglePasswordVisibility('inputNewPassword')">
																	<i class="bx bx-hide"></i>
																</a>
															</div>
														</div>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelButton">Cancel</button>
														<button type="button" class="btn btn-primary" id="submitButton" onclick="validateCurrentPassword()">Submit</button>
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
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer">
			<p class="mb-0">Copyright © 2021. All right reserved.</p>
		</footer>
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
	<!-- Bootstrap JS -->
	<script>
		function togglePasswordVisibility(inputId) {
			const passwordField = document.getElementById(inputId);
			if (passwordField.type === "password") {
				passwordField.type = "text";
			} else {
				passwordField.type = "password";
			}
		}

		function enableEditing() {
			// Enable input fields
			document.querySelectorAll('input').forEach(input => input.removeAttribute('disabled'));

			// Swap button styles
			const editBtn = document.getElementById('edit-btn');
			const saveBtn = document.getElementById('save-btn');

			editBtn.classList.remove('btn-primary');
			editBtn.classList.add('btn-outline-secondary');
			saveBtn.classList.remove('btn-outline-secondary');
			saveBtn.classList.add('btn-primary');

			// Enable Save button, disable Edit button
			saveBtn.removeAttribute('disabled');
			editBtn.setAttribute('disabled', true);
		}

		function saveProfile() {

			// Disable input fields again
			document.querySelectorAll('input').forEach(input => input.setAttribute('disabled', true));

			// Swap button styles back
			const editBtn = document.getElementById('edit-btn');
			const saveBtn = document.getElementById('save-btn');

			saveBtn.classList.remove('btn-primary');
			saveBtn.classList.add('btn-outline-secondary');
			editBtn.classList.remove('btn-outline-secondary');
			editBtn.classList.add('btn-primary');

			// Disable Save button, enable Edit button
			saveBtn.setAttribute('disabled', true);
			editBtn.removeAttribute('disabled');
		}

		const cancelButton = document.getElementById("cancelButton");
		const submitButton = document.getElementById("submitButton");
		const inputCurrentPassword = document.getElementById("inputCurrentPassword");
		const newPasswordContainer = document.getElementById("newPasswordContainer");
		const inputNewPassword = document.getElementById("inputNewPassword");

		// Reset Modal on Cancel
		cancelButton.addEventListener("click", () => {
			// Reset all fields and button text to default
			inputCurrentPassword.value = "";
			inputNewPassword.value = "";
			document.getElementById("newPasswordSection").style.display = "none"; // Hide new password input
			submitButton.textContent = "Submit"; // Reset button to "Submit"
		});

		// Validate current password and show new password input if correct
		function validateCurrentPassword() {
			const currentPassword = document.getElementById("inputCurrentPassword").value;

			// Send the current password to the backend for validation
			fetch("validate-password.php", {
					method: "POST",
					headers: {
						"Content-Type": "application/json"
					},
					body: JSON.stringify({
						currentPassword
					})
				})
				.then(response => response.json())
				.then(data => {
					if (data.success) {
						// Show the New Password section if validation is successful
						document.getElementById("newPasswordSection").style.display = "block";
						document.querySelector(".modal-title").textContent = "Set New Password";

						// Change button text to "Save Changes"
						const submitButton = document.getElementById("submitButton");
						submitButton.textContent = "Save Changes";
						submitButton.onclick = saveNewPassword; // Change button action to save new password
					} else {
						alert("Current password is incorrect. Please try again.");
					}
				})
				.catch(error => console.error("Error:", error));
		}

		// Send the new password to PHP for updating
		function saveNewPassword() {
			const newPassword = document.getElementById("inputNewPassword").value;

			// Send the new password to the backend for updating
			fetch("save-new-password.php", {
					method: "POST",
					headers: {
						"Content-Type": "application/json"
					},
					body: JSON.stringify({
						newPassword
					})
				})
				.then(response => response.json())
				.then(data => {
					if (data.success) {
						alert("Password updated successfully!");

						const modalElement = document.getElementById("exampleVerticallycenteredModal");
						const modalInstance = bootstrap.Modal.getInstance(modalElement);
						if (modalInstance) {
							modalInstance.hide();
						}

						inputCurrentPassword.value = "";
						inputNewPassword.value = "";
						document.getElementById("newPasswordSection").style.display = "none"; // Hide new password input
						submitButton.textContent = "Submit"; // Reset button to "Submit"
					} else {
						alert("Error updating password. Please try again.");
					}
				})
				.catch(error => console.error("Error:", error));
		}
	</script>
	<?php include '../include/bootstrap-script.php'; ?>

</body>

</html>