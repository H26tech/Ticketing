<?php
include '../include/connection/connection.php';
include '../include/connection/session.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM sponsor WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $sponsor = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../include/head.php'; ?>
</head>
<body>
    <div class="wrapper">
        <?php include '../include/admin/sidebar.php'; ?>
        <?php include '../include/admin/header.php'; ?>

        <div class="page-wrapper">
            <div class="page-content">
                <div class="card">
                    <div class="card-body">
                        <form action="logic.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $sponsor['id']; ?>">
                            <div class="mb-3">
                                <label class="form-label">Sponsor Name</label>
                                <input type="text" class="form-control" name="nama_sponsor" value="<?php echo $sponsor['nama_sponsor']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sponsor Logo</label>
                                <input type="file" class="form-control" name="foto_logo">
                                <img src="../uploads/<?php echo $sponsor['foto_logo']; ?>" alt="Current Logo" style="width: 50px;">
                            </div>
                            <button type="submit" name="update" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../include/bootstrap-script.php'; ?>
    </div>
</body>
</html>
