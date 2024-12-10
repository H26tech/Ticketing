<?php
include '../include/connection/connection.php';

if (isset($_POST['edit_banner'])) {
    $banner_id = $_POST['banner_id'];
    $banner_title = $_POST['banner_title'];
    $banner_desc = $_POST['banner_desc'];
    $status = isset($_POST['status']) ? 'published' : 'draft';

    // Image file handling
    $image_content = '';
    if (isset($_FILES['image_content']) && $_FILES['image_content']['error'] === UPLOAD_ERR_OK) {
        $imageName = $_FILES['image_content']['name'];
        $imageTmp = $_FILES['image_content']['tmp_name'];
        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        $newImageName = uniqid() . '.' . $imageExtension;
        $uploadPath = '../uploads/' . $newImageName;

        if (move_uploaded_file($imageTmp, $uploadPath)) {
            $image_content = $newImageName;
        }
    }

    // Update query, updating only modified fields
    if ($image_content) {
        $query = "UPDATE `banner-content` SET banner_title = ?, banner_desc = ?, image_content = ?, status = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssssi', $banner_title, $banner_desc, $image_content, $status, $banner_id);
    } else {
        $query = "UPDATE `banner-content` SET banner_title = ?, banner_desc = ?, status = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssi', $banner_title, $banner_desc, $status, $banner_id);
    }

    if ($stmt->execute()) {
        header('Location: ../banner-content/');
    } else {
        header('Location: ../banner-content/');
    }
    exit();
}
?>
