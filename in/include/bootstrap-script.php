<script src="../assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="../assets/js/jquery.min.js"></script>
	<script src="../assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="../assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="../assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="../assets/plugins/fancy-file-uploader/jquery.ui.widget.js"></script>
	<script src="../assets/plugins/fancy-file-uploader/jquery.fileupload.js"></script>
	<script src="../assets/plugins/fancy-file-uploader/jquery.iframe-transport.js"></script>
	<script src="../assets/plugins/fancy-file-uploader/jquery.fancy-fileupload.js"></script>
	<script src="../assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js"></script>
	<script src="../assets/js/form-file-upload.js"></script>
	<script src="../assets/plugins/input-tags/js/tagsinput.js"></script>
	<script src="../assets/plugins/OwlCarousel/js/owl.carousel.min.js"></script>
	<script src="../assets/plugins/OwlCarousel/js/owl.carousel2.thumbs.min.js"></script>
	<script src="../assets/js/product-details.js"></script>
	<script src="../assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="../assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
	<script src="assets/js/table-datatable.js"></script>
	<!--Password show & hide js -->
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>
	
	<!--app JS-->
	<script src="../assets/js/app.js"></script>