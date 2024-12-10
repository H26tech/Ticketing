<div style="font-size: 12px; padding: 10px;">
    <header>
        <div style="text-align: center;">
            <img src="../assets/images/BFF-logo.png" width="170" alt="Logo" />
            <h2>Bluvocation Film Fest</h2>
            <p>Jl. Raden Saleh, Kec. Karang Tengah, Kota Tangerang, Banten 15157</p>
            <p>021-73455777 | smk@budiluhur.sch.id</p>
        </div>
    </header>
    <main>
        <h3>Invoice To: <?php echo htmlspecialchars($fullName); ?></h3>
        <p>Phone: <?php echo htmlspecialchars($phoneNumber); ?></p>
        <p>Email: <a href="mailto:<?php echo htmlspecialchars($email); ?>"><?php echo htmlspecialchars($email); ?></a></p>
        <h4>Invoice #<?php echo $paymentDetails['id']; ?></h4>
        <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><?php echo $orderDescription; ?></td>
                    <td><?php echo htmlspecialchars($totalAmount / count($selectedSeats)); ?></td>
                    <td><?php echo count($selectedSeats); ?></td>
                    <td><?php echo htmlspecialchars($totalAmount); ?></td>
                </tr>
                <?php if ($packageAmount > 0): ?>
                <tr>
                    <td>2</td>
                    <td><?php echo $packageDescription; ?></td>
                    <td><?php echo $packageAmount; ?></td>
                    <td>1</td>
                    <td><?php echo $packageAmount; ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <p><strong>Subtotal:</strong> <?php echo htmlspecialchars($subTotal); ?></p>
        <p><strong>Tax:</strong> <?php echo htmlspecialchars($totalTax); ?></p>
        <p><strong>Grand Total:</strong> <?php echo htmlspecialchars($grandTotal); ?></p>
        <p>Thank you for your purchase!</p>
    </main>
    <footer>
        <p>Invoice was created on a computer and is valid without a signature.</p>
    </footer>
</div>
