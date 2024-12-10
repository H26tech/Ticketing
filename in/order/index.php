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
        /* Supaya mengikuti konten */
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Topbar Styling */
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
        grid-template-columns: repeat(19, 1fr);
        gap: 2px;
        max-width: 750px;
        margin: 0 auto;
    }

    .seat-row {
        display: contents;
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
        width: 15px;
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
    }

    .ticket-info-container {
        display: flex;
        align-items: center;
        gap: 10px;
        /* Adjust spacing between price and seats */
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
                        <!-- <div class="legend-item">
                            <div class="box on-booking"></div>On Booking
                        </div> -->
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
                <div class="screen">SCREEN</div>

                <div class="ticket-info-wrapper">
                    <div class="ticket-info-container">
                        <div class="ticket-price-container" id="totalPrice">Rp 0</div>
                        <div class="gap"></div> <!-- Optional gap -->
                        <div class="ticket-price-container" id="selectedSeatsText">Seats: None</div>
                    </div>
                </div>

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
                                    $correctSeats = 17;
                                    for ($seatCounter = 18; $seatCounter--;) {

                                        $seatData = mysqli_fetch_assoc($result);

                                        if ($seatCounter > 13) {
                                            $seatNumber = $correctSeats - 1;
                                        } else {
                                            $seatNumber = $correctSeats;
                                        }
                                        $isBooked = $seatData['is_booked'] ?? 0;
                                        $seatClass = $isBooked ? 'seat booked' : 'seat available';
                                        if ($seatCounter == 13) {
                                            $seatNumber = $correctSeats--;
                                            echo "<div class='gap'></div>";
                                        } else if ($seatCounter < $endSeat) {
                                            echo "<div class='gap'></div>";
                                        } else {
                                            echo "<div class='$seatClass' data-seat-number='$rowLetter$seatNumber'>$seatNumber</div>";
                                            $correctSeats--;
                                        };
                                    }
                                    echo "</div>";
                                }
                                mysqli_close($conn);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="bayar.php">
                    <input type="hidden" name="selectedSeats" id="selectedSeats">
                    <div class="btn-group">
                        <button type="submit" class="confirm-btn">Pesan Sekarang</button>
                        <button type="button" class="cancel-btn" onclick="window.location.href='../homepage/index.php'">Cancel</button>
                    </div>
                </form>

            </div>
        </div>

        <script>
            const seatPrice = 40000;

            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('seat') && !event.target.classList.contains('booked') && !event.target.classList.contains('not-sale')) {
                    event.target.classList.toggle('selected');
                    updateSelectedSeats();
                }
            });

            function updateSelectedSeats() {
                const selectedSeats = Array.from(document.querySelectorAll('.seat.selected'))
                    .map(seat => seat.dataset.seatNumber);
                document.getElementById('selectedSeats').value = selectedSeats.join(',');

                const totalPrice = selectedSeats.length * seatPrice;
                document.getElementById('totalPrice').textContent = `Rp ${totalPrice.toLocaleString()}`;
                document.getElementById('selectedSeatsText').textContent = `Seats: ${selectedSeats.length ? selectedSeats.join(', ') : 'None'}`;
            }

            // Tambahkan fungsi ini untuk mencegah form submit jika tidak ada kursi yang dipilih
            document.querySelector('form').addEventListener('submit', function(event) {
                const selectedSeats = document.getElementById('selectedSeats').value;
                if (!selectedSeats) {
                    event.preventDefault(); // Mencegah submit form
                    alert('Silakan pilih kursi terlebih dahulu sebelum memesan.');
                }
            });
        </script>


        <?php include '../include/bootstrap-script.php'; ?>
    </div>

</body>

</html>