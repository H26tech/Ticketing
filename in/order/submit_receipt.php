<?php
include '../include/connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['fullName'];
    $paymentMethod = $_POST['paymentMethod'];
    $selectedSeats = $_POST['selectedSeats'];
    $amount = $_POST['amount'];

    // Get the selected package value
    $packageValue = $_POST['packages'] ?? 0; // Default to 0 if no package is selected
    
    // Handle file upload
    $receiptImage = $_FILES['receipt'];
    $targetDir = "../uploads/";
    $receiptFileName = time() . '_' . basename($receiptImage["name"]);
    $targetFile = $targetDir . $receiptFileName;

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Check if any selected seats are already booked
        $seatsArray = explode(',', $selectedSeats);
        $movieId = null;

        foreach ($seatsArray as $seat) {
            $rowLetter = substr($seat, 0, 1); // Row letter
            $seatNumber = (int)substr($seat, 1); // Seat number

            // Get the movie_id for the selected seat
            $checkSeat = $conn->prepare("SELECT is_booked, movie_id FROM seats WHERE seat_number = ? AND row_letter = ? FOR UPDATE");
            $checkSeat->bind_param("is", $seatNumber, $rowLetter);
            $checkSeat->execute();
            $result = $checkSeat->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                if ($row['is_booked'] == 1) {
                    // Redirect to index.php with an error message
                    $_SESSION['error'] = "Kursi $seat sudah dipesan. Silakan pilih kursi lain.";
                    header("Location: ../index.php");
                    exit();
                }

                // Get the movie_id from the first available seat (assuming all selected seats are for the same movie)
                if ($movieId === null) {
                    $movieId = $row['movie_id'];
                }
            }
            $checkSeat->close();
        }

        // If file upload is successful
        if (move_uploaded_file($receiptImage["tmp_name"], $targetFile)) {
            // Insert payment data including movie_id and package
            $sql = "INSERT INTO payments (full_name, payment_method, selected_seats, amount, receipt_image, status, movie_id, package) 
                    VALUES (?, ?, ?, ?, ?, 'pending', ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssisii", $fullName, $paymentMethod, $selectedSeats, $amount, $receiptFileName, $movieId, $packageValue);
            $stmt->execute();
            $stmt->close();

            // Update seat status to booked
            $sqlUpdate = "UPDATE seats SET is_booked = 1 WHERE seat_number = ? AND row_letter = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);

            foreach ($seatsArray as $seat) {
                $rowLetter = substr($seat, 0, 1);
                $seatNumber = (int)substr($seat, 1);
                $stmtUpdate->bind_param("is", $seatNumber, $rowLetter);
                $stmtUpdate->execute();
            }

            $stmtUpdate->close();

            // Commit transaction
            $conn->commit();

            // Redirect to history page with a success message
            $_SESSION['success'] = "Pembayaran Anda sedang diproses. Terima kasih!";
            header("Location: history.php");
            exit();
        } else {
            throw new Exception("Upload bukti transfer gagal.");
        }
    } catch (Exception $e) {
        // Rollback if there's an error
        $conn->rollback();
        $_SESSION['error'] = "Gagal memproses pemesanan: " . $e->getMessage();
        header("Location: ../index.php");
    }

    $conn->close();
}
?>
