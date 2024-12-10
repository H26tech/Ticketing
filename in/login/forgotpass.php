<!-- HTML Head -->
<?php include '../include/head.php'; ?>

<body class="bg-forgot">
	<!-- wrapper -->
	<div class="wrapper">
		<div class="authentication-forgot d-flex align-items-center justify-content-center">
			<div class="card forgot-box">
				<div class="card-body">
					<div class="p-4 rounded border">
						<div class="text-center">
							<img src="assets/images/icons/forgot-2.png" width="120" alt="" />
						</div>
						<h4 class="mt-5 font-weight-bold">Forgot Password?</h4>
						<p class="text-muted">Enter your registered email ID to reset the password</p>
						<form method="POST" action="process_forgotpass.php">
							<div class="my-4">
								<label class="form-label">Email ID</label>
								<input type="email" name="email" class="form-control form-control-lg" placeholder="example@user.com" required />
							</div>
							<div class="d-grid gap-2">
								<button type="submit" class="btn btn-primary btn-lg">Send</button> 
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
