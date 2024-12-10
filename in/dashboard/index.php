<!-- HTML Head -->
<?php include '../include/head.php'; ?>
<?php include '../include/connection/session.php';

if ($_SESSION['role'] != 'admin') {
	// If the user is an admin, redirect to the dashboard page
	header('Location: ../order/');
	exit();
}

?>
<body>
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		<?php include '../include/admin/sidebar.php';?>
		<!--end sidebar wrapper -->
		<!--start header -->
		<?php include '../include/admin/header.php';?>
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
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				<div class="container">
					<div class="main-body">
						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-body">
										<div class="d-flex flex-column align-items-center text-center">
											<img src="../uploads/<?php echo $_SESSION['profile_image'];?>" alt="Admin" class="rounded-circle p-1 bg-primary" width="110">
											<div class="mt-3">
												<h4><?php echo $_SESSION['full_name'];?></h4>
												<p class="text-secondary mb-1"><?php echo $_SESSION['email']; ?></p>
												<p class="text-muted font-size-sm"><?php echo $_SESSION['phone_number']; ?></p>
											</div>

										</div>
									</div>
									<hr class="my-4" />
									<div class="d-flex flex-column align-items-center text-center">
										<h3 class="mb-3 mt-0">Hello <?php echo $_SESSION['full_name']; ?>!</h3>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="">
				<div class="container py-2">
					<h2 class="font-weight-light text-center text-primary py-3">What Admin Can Do?</h2>
					<!-- timeline item 1 -->
					<!-- timeline item 1 -->
					<div class="row" id="timeline-1">
						<div class="col-auto text-center flex-column d-none d-sm-flex">
							<div class="row h-50">
								<div class="col">&nbsp;</div>
								<div class="col">&nbsp;</div>
							</div>
							<h5 class="m-2">
								<span class="badge rounded-pill bg-light border timeline-badge" data-timeline="1">&nbsp;</span>
							</h5>
							<div class="row h-50">
								<div class="col border-end">&nbsp;</div>
								<div class="col">&nbsp;</div>
							</div>
						</div>
						<div class="col py-2">
							<div class="card radius-15">
								<div class="card-body">
									<div class="float-end timeline-date" data-timeline="1"></div>
									<h4 class="card-title timeline-title" data-timeline="1">Step 1, User Checking</h4>
									<p class="card-text">You can check all users data and information in <a href="../users/">All Users</a> menu or just click <a href="http://">HERE.</a> </p>
								</div>
							</div>
						</div>
					</div>

					<!-- timeline item 2 -->
					<div class="row" id="timeline-2">
						<div class="col-auto text-center flex-column d-none d-sm-flex">
							<div class="row h-50">
								<div class="col border-end">&nbsp;</div>
								<div class="col">&nbsp;</div>
							</div>
							<h5 class="m-2">
								<span class="badge rounded-pill bg-light timeline-badge" data-timeline="2">&nbsp;</span>
							</h5>
							<div class="row h-50">
								<div class="col border-end">&nbsp;</div>
								<div class="col">&nbsp;</div>
							</div>
						</div>
						<div class="col py-2">
							<div class="card border-primary shadow radius-15">
								<div class="card-body">
									<div class="float-end timeline-date" data-timeline="2"></div>
									<h4 class="card-title timeline-title" data-timeline="2">Step 2, Manage Content</h4>
									<p class="card-text">Managing content such as Banner and Instagram post is really easy. Organize the Banner in <a href="../banner-content/">Banner Content</a> menu and Instagram Post in the <a href="../instagram-post/">Instagram Post</a> menu.
								<br>Uploading Movies can be done in <a href="../movie-insert/">HERE!</a></p>
								</div>
							</div>
						</div>
					</div>

					<!-- timeline item 3 -->
					<div class="row" id="timeline-3">
						<div class="col-auto text-center flex-column d-none d-sm-flex">
							<div class="row h-50">
								<div class="col border-end">&nbsp;</div>
								<div class="col">&nbsp;</div>
							</div>
							<h5 class="m-2">
								<span class="badge rounded-pill bg-light border timeline-badge" data-timeline="3">&nbsp;</span>
							</h5>
							<div class="row h-50">
								<div class="col border-end">&nbsp;</div>
								<div class="col">&nbsp;</div>
							</div>
						</div>
						<div class="col py-2">
							<div class="card radius-15">
								<div class="card-body">
									<div class="float-end timeline-date" data-timeline="3">Wed, Jan 11th 2019 8:30 AM</div>
									<h4 class="card-title timeline-title" data-timeline="3">Step 3, Approving Payments</h4>
									<p class="card-text">After customer book your tickets, you can check if they pay correctly and <mark>approve their payment in <a href="../payment/">Payment</a> menu</mark>. You can also <mark> reject their payment if the payment is not Valid or Fraud</mark></p>
								</div>
							</div>
						</div>
					</div>

					<!-- timeline item 4 -->
					<div class="row" id="timeline-4">
						<div class="col-auto text-center flex-column d-none d-sm-flex">
							<div class="row h-50">
								<div class="col border-end">&nbsp;</div>
								<div class="col">&nbsp;</div>
							</div>
							<h5 class="m-2">
								<span class="badge rounded-pill bg-light border timeline-badge" data-timeline="4">&nbsp;</span>
							</h5>
							<div class="row h-50">
								<div class="col border-end">&nbsp;</div>
								<div class="col">&nbsp;</div>
							</div>
						</div>
						<div class="col py-2">
							<div class="card radius-15">
								<div class="card-body">
									<div class="float-end timeline-date" data-timeline="4"></div>
									<h4 class="card-title timeline-title" data-timeline="4">Step 4, Exporting Data</h4>
									<p class="card-text">After the payment is all done, <mark>you can export the payment data.</mark> It's <mark>above the payment table</mark> in <a href="http://">Payment Menu.</a></p>
								</div>
							</div>
						</div>
					</div>

					<!--/row-->
				</div>
				<!--container-->
				<hr>
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
	<?php include '../include/bootstrap-script.php'; ?>
</body>

</html>