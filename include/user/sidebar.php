<div class="sidebar-wrapper" data-simplebar="true">
	<div class="sidebar-header">
		<div>
			<img src="../assets/images/BFF-logo-notext.png" class="logo-icon" alt="logo icon">
		</div>
<div>
					<h4 class="logo-text">Bluvocation</h4>
				</div>
		<div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
		</div>
	</div>
	<!--navigation-->
	<ul class="metismenu" id="menu">
		<li>
			<a href="../homepage/">
				<div class="parent-icon"><i class='bx bx-home-circle'></i>
				</div>
				<div class="menu-title">Home</div>
			</a>
		</li>
		<li class="menu-label">Ticket Orders</li>
		<li>
			<a href="../order/">
				<div class="parent-icon"><i class="bx bx-donate-blood"></i>
				</div>
				<div class="menu-title">Order Ticket</div>
			</a>
		</li>
		<li>
			<a href="../order/history.php">
				<div class="parent-icon"><i class='bx bx-message-square-edit'></i>
				</div>
				<div class="menu-title">Order History</div>
			</a>
		</li>
		<li class="menu-label">Personal</li>
		<li>
			<a href="../dashboard/profile.php">
				<div class="parent-icon"><i class="bx bx-user-circle"></i>
				</div>
				<div class="menu-title">User Profile</div>
			</a>
		</li>
		<li class="menu-label">Others</li>
		<?php if (isset($_SESSION['user_id'])): ?>
			<li>
				<a href="..\include\connection\logout.php">
					<div class="parent-icon"><i class="bx bx-log-out-circle"></i>
					</div>
					<div class="menu-title">Logout</div>
				</a>
			</li>
		<?php else: ?>
			<li>
				<a href="../login/">
					<div class="parent-icon"><i class="bx bx-log-out-circle"></i>
					</div>
					<div class="menu-title">Login</div>
				</a>
			</li>
		<?php endif; ?>
	</ul>
	<!--end navigation-->
</div>