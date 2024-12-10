<?php
include '../include/connection/connection.php';
?>
<?php
include '../include/connection/session.php';
?>
<?php include '../include/head.php'; ?>

<style>
    /* Container styling */
    .ticket-info-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        text-align: center;
        gap: 10px;
        margin: 20px 0;
    }

    .ticket-price-container {
        display: inline-block;
        border: 1px solid #d9534f;
        border-radius: 4px;
        padding: 10px 15px;
        font-size: 18px;
        color: #d9534f;
        background-color: #fff;
        width: fit-content;
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
    }

    .topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #f8f9fa;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        padding: 10px;
    }

    .topbar h2 {
        font-size: 2vw;
        color: #333;
        margin: 0;
    }

    .legend {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-right: 10px;
    }

    .legend .box {
        width: 15px;
        height: 15px;
        border-radius: 3px;
    }

    .navbar {
        width: 100%;
    }

    .screen {
        background-color: #ccc;
        width: 90%;
        margin: 0 auto;
        height: 30px;
        line-height: 30px;
        text-align: center;
        font-weight: bold;
        color: #333;
        margin-bottom: 15px;
    }

    .seat-container {
        display: grid;
        flex-direction: column;
        /* Vertikal untuk setiap baris */
        gap: 10px;
        /* Space between each row */
        max-width: 750px;
        margin: 0 auto;
        align-items: flex-start;
        /* Setiap baris rata kiri */
    }

    .seat-row {
        display: flex;
        justify-content: flex-start;
        /* Kursi di setiap baris rata kiri */
        align-items: center;
        width: 100%;
        margin-bottom: 5px;
        /* Add space between rows */
        border-bottom: 1px solid #ccc;
        /* Horizontal line between rows */
    }

    .row-label {
        width: 20px;
        text-align: right;
        font-weight: bold;
        margin-right: 8px;
    }

    .seat {
        width: 30px;
        height: 30px;
        font-size: 12px;
        line-height: 30px;
        text-align: center;
        color: white;
        border-radius: 3px;
        cursor: pointer;
        margin: 1px;
        /* Add margin between seats */
    }

    .available {
        background-color: #4CAF50;
    }

    .selected {
        background-color: #FFEB3B;
    }

    .on-booking {
        background-color: #2196F3;
    }

    .booked {
        background-color: #F44336;
        cursor: not-allowed;
    }

    .your-seats {
        background-color: #FFEB3B;
    }

    .sold {
        background-color: #F44336;
    }

    .not-sale {
        background-color: #BDBDBD;
        cursor: not-allowed;
    }

    .btn-group {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 25px;
    }

    .confirm-btn,
    .cancel-btn {
        padding: 12px 24px;
        font-size: 16px;
        font-weight: bold;
        border: none;
        color: white;
        border-radius: 4px;
        cursor: pointer;
    }

    .confirm-btn:hover {
        background-color: #5bc0de;
        transition: background-color 0.3s;
    }

    .confirm-btn {
        background-color: #008cff;
    }

    .cancel-btn:hover {
        background-color: #d9534f;
        transition: background-color 0.3s;
    }

    .cancel-btn {
        background-color: #f41127;
    }

    .gap {
        width: 30px;
        /* Adjust the gap width here */
        height: 30px;
        /* Make the gap the same size as a seat */
    }

    @media (max-width: 768px) {
        .topbar {
            flex-direction: column;
            text-align: center;
            height: 90px;
        }

        .topbar h2 {
            font-size: 5vw;
        }

        .legend {
            justify-content: center;
            flex-wrap: wrap;
        }

        .seat-container {
            max-width: 90%;
        }
    }

    .ticket-info-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }
</style>

<body>
    <div class="wrapper toggled">
        <div class="page-wrapper">
            <header>
                <div class="topbar" style="left: 0px;">
                    <h2 class="card-title">Order Your Seat</h2>
                    <div class="legend">
                        <div class="legend-item">
                            <div class="box available"></div>Available
                        </div>
                        <div class="legend-item">
                            <div class="box your-seats"></div>Selected Seats
                        </div>
                        <div class="legend-item">
                            <div class="box sold"></div>Sold
                        </div>
                        <div class="legend-item">
                            <div class="box not-sale"></div>Not Sale
                        </div>
                    </div>
                </div>
            </header>
            <br>

            <div class="page-content">

                <div class="ticket-info-wrapper">
                    <div class="ticket-info-container">
                        <div class="ticket-price-container" id="totalPrice">Rp 0</div>
                        <div class="gap"></div> <!-- Optional gap -->
                        <div class="ticket-price-container" id="selectedSeatsText">Seats: None</div>
                    </div>
                </div>
                <div class="screen">SCREEN</div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="seat-container" id="seatContainer">
                                <?php
                                $rows = ['K', 'J', 'I', 'H', 'G', 'F', 'E', 'D', 'C', 'B'];
                                $seatLimits = [
                                    'K' => 3,
                                    'J' => 3,
                                    'I' => 3,
                                    'H' => 3,
                                    'G' => 3,
                                    'F' => 3,
                                    'E' => 3,
                                    'D' => 3,
                                    'C' => 3,
                                    'B' => 1
                                ];

                                foreach ($rows as $rowLetter) {
                                    echo "<div class='seat-row'>";
                                    echo "<div class='row-label'><strong>$rowLetter</strong></div>";

                                    $query = "SELECT seat_number, is_booked FROM seats WHERE row_letter = '$rowLetter' ORDER BY seat_number DESC";
                                    $result = mysqli_query($conn, $query);
                                    $endSeat = $seatLimits[$rowLetter];

                                    $seatsInRow = []; // To store the seats for each row
                                    while ($seatData = mysqli_fetch_assoc($result)) {
                                        $seatsInRow[] = $seatData;
                                    }

                                    // Now, render the seats and introduce the gap between 13 and 12 in each row
                                    foreach ($seatsInRow as $seatData) {
                                        $seatNumber = $seatData['seat_number'];
                                        $isBooked = $seatData['is_booked'] ?? 0; // Check if the seat is booked
                                        $seatClass = $isBooked ? 'seat booked' : 'seat available';

                                        // Check if the seat is 13 and the next seat is 12
                                        if ($seatNumber == 12) {
                                            // Create a gap before seat 12
                                            echo "<div class='seat gap'>&nbsp;</div>"; // Empty space
                                        }

                                        // Now render the current seat
                                        echo "<div class='$seatClass' data-seat-number='$rowLetter$seatNumber'>$seatNumber</div>";
                                    }

                                    echo "</div>";
                                }
                                mysqli_close($conn);
                                ?>


                            </div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="bayar.php" onsubmit="return validateSelection()">
                    <input type="hidden" name="selectedSeats" id="selectedSeats">
                    <div class="btn-group">
                        <button type="submit" class="confirm-btn">Pesan Sekarang</button>
                        <button type="button" class="cancel-btn" onclick="window.location.href='../homepage/index.php'">Cancel</button>
                    </div>
                </form>

                <script>
                    const seatPrice = 50000;

                    document.addEventListener('click', function(event) {
                        if (event.target.classList.contains('available')) {
                            event.target.classList.toggle('selected');
                            updateTotalPrice();
                        }
                    });

                    function updateTotalPrice() {
                        const selectedSeats = document.querySelectorAll('.selected');
                        const totalPrice = selectedSeats.length * seatPrice;
                        const selectedSeatsText = Array.from(selectedSeats).map(seat => seat.getAttribute('data-seat-number')).join(', ') || 'None';
                        document.getElementById('totalPrice').textContent = `Rp ${totalPrice.toLocaleString()}`;
                        document.getElementById('selectedSeatsText').textContent = `Seats: ${selectedSeatsText}`;
                        document.getElementById('selectedSeats').value = selectedSeatsText;
                    }

                    // Fungsi untuk memvalidasi apakah kursi sudah dipilih
                    function validateSelection() {
                        const selectedSeats = document.querySelectorAll('.selected');
                        if (selectedSeats.length === 0) {
                            alert("Select the seat first!");
                            return false; // Menghentikan pengiriman form
                        }
                        return true; // Melanjutkan pengiriman form
                    }
                </script>

            </div>
            <?php include '../include/bootstrap-script.php'; ?>
</body>

</html>