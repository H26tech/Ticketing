<?php
include '../include/connection/connection.php';
include '../include/connection/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['fullName'];
    $paymentMethod = $_POST['paymentMethod'];
    $selectedSeats = $_POST['selectedSeats'];
    $amount = (int)str_replace(' IDR', '', $_POST['amount']); // Remove "IDR" and cast to integer
    $packages = $_POST['packages'] ?? []; // Retrieve package data as array

    // File upload handling
    $receiptImage = $_FILES['receipt'];
    $targetDir = "../uploads/";
    $receiptFileName = time() . '_' . basename($receiptImage["name"]);
    $targetFile = $targetDir . $receiptFileName;

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Prepare array for storing seats and packages
        $seatsArray = explode(',', str_replace(' ', '', $selectedSeats));
        $movieId = null;
        $packageDetails = [];

        // Step 1: Check all selected seats for availability
        foreach ($seatsArray as $index => $seat) {
            $rowLetter = substr($seat, 0, 1);
            $seatNumber = (int)substr($seat, 1);

            // Lock the seat record for update and check booking status
            $checkSeat = $conn->prepare("SELECT is_booked, movie_id FROM seats WHERE seat_number = ? AND row_letter = ? FOR UPDATE");
            $checkSeat->bind_param("is", $seatNumber, $rowLetter);
            $checkSeat->execute();
            $result = $checkSeat->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                if ($row['is_booked'] == 1) {
                    $_SESSION['error'] = "Kursi $seat sudah dipesan. Silakan pilih kursi lain.";
                    header("Location: ../index.php");
                    exit();
                }

                if ($movieId === null) {
                    $movieId = $row['movie_id'];
                }
            }
            $checkSeat->close();

            // Add seat and package details to packageDetails array
            $packageDetails[] = [
                'seat' => $seat,
                'package' => isset($packages[$index]) && $packages[$index] == 50000 ? true : false
            ];
        }

        // Encode package details as JSON
        $packageDetailsJson = json_encode($packageDetails);

        // Step 2: If file upload successful, proceed with database entries
        if (move_uploaded_file($receiptImage["tmp_name"], $targetFile)) {
            // Insert payment information
            $sql = "INSERT INTO payments (full_name, payment_method, selected_seats, amount, receipt_image, status, movie_id, package_details) 
                    VALUES (?, ?, ?, ?, ?, 'pending', ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssisis", $fullName, $paymentMethod, $selectedSeats, $amount, $receiptFileName, $movieId, $packageDetailsJson);
            $stmt->execute();
            $stmt->close();

            // Step 3: Update seat status to booked for all selected seats
            $sqlUpdate = "UPDATE seats SET is_booked = 1 WHERE seat_number = ? AND row_letter = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);

            foreach ($seatsArray as $seat) {
                $rowLetter = substr($seat, 0, 1);
                $seatNumber = (int)substr($seat, 1);
                $stmtUpdate->bind_param("is", $seatNumber, $rowLetter);
                $stmtUpdate->execute();
            }
            $stmtUpdate->close();

            // Commit the transaction
            $conn->commit();

            $_SESSION['success'] = "Pembayaran Anda sedang diproses. Terima kasih!";
            header("Location: history.php");
            exit();
        } else {
            throw new Exception("Upload bukti transfer gagal.");
        }
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Gagal memproses pemesanan: " . $e->getMessage();
        header("Location: ../index.php");
    }

    $conn->close();
}
?>
