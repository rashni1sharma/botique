<?php
include ('./conn/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id'])) {
        echo "id not found!";
        exit();
    }

    $id = $_POST['id'];

    $sql = "SELECT * FROM clothes WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $invoice_no = $id . time();
        $title = $row['title'];
        $total = $row['price'];
        $created_at = date('Y-m-d H:i:s');
    } else {
        echo "Clothes not found!";
        exit();
    }

    // ... (rest of your eSewa integration code)

} else {
    echo "Invalid request!";
    exit();
}

    // Secret Key provided by eSewa
    $secret_key = "8gBm/:&EnhH.1/q";

    // Create a message to be signed in the exact order required by eSewa
    $message = "total_amount={$total},transaction_uuid={$invoice_no},_code=EPAYTEST";

    // Generate the HMAC signature
    $signature = hash_hmac('sha256', $message, $secret_key, true);
    $signature_base64 = base64_encode($signature);

    // Save the order details in the database
    $sql = "INSERT INTO orders (clothes_id, invoice_no, total, status, created_at) VALUES ('$clothes_id', '$invoice_no', '$total', 'pending', '$created_at')";
    if (!$conn->query($sql))
        die("Error: " . $conn->error);
 else {
    echo "Invalid request!";
    exit();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<!-- Header -->
<header class="header">
    <h1 class="header-title">E-Pasal</h1>
    <nav class="header-nav">
        <a href="#">Home</a>
        <a href="#">Products</a>
        <a href="#">Cart</a>
        <a href="#">Contact</a>
    </nav>
</header>

<!-- Main Content -->
<main class="checkout-container">
<section class="order-details">
    <h2>Order Summary</h2>
    <img alt="product-image" src="<?php echo $row['image'] ?>" class="order-image" />
    <p><strong>Product Name:</strong> <?php echo $title ?></p>
    <p><strong>Price:</strong> Rs. <?php echo $total; ?></p>
    <p><strong>Invoice No:</strong> <?php echo $invoice_no; ?></p>
</section>
<hr>
<section class="payment-options">
<h2>Payment Options</h2>
<ul>
<li>
<form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
    <input type="hidden" id="amount" name="amount" value="<?php echo $total; ?>">
    <input type="hidden" id="tax_amount" name="tax_amount" value="0">
    <input type="hidden" id="total_amount" name="total_amount" value="<?php echo $total; ?>">
    <input type="hidden" id="transaction_uuid" name="transaction_uuid" value="<?php echo $invoice_no; ?>">
    <input type="hidden" id="product_code" name="product_code" value="EPAYTEST">
    <input type="hidden" id="product_service_charge" name="product_service_charge" value="0">
    <input type="hidden" id="product_delivery_charge" name="product_delivery_charge" value="0">
    <input type="hidden" id="success_url" name="success_url" value="http://localhost/EsewaIntegration/result.php">
    <input type="hidden" id="failure_url" name="failure_url" value="http://localhost/EsewaIntegration/result.php">
    <input type="hidden" id="signed_field_names" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
    <input type="hidden" id="signature" name="signature" value="<?php echo $signature_base64; ?>">
    <input class="payment-icon" type="image" src="images/esewa_og.webp">
</form>
</li>
<li>
    <img class="payment-icon" src="images/khalti.png" alt="Khalti">
</li>
<li>
    <img class="payment-icon" src="images/imepay.png" alt="IME Pay">
</li>
<li>
    <img class="payment-icon" src="images/fonepay.png" alt="Fonepay">
</li>
<li>
    <img class="payment-icon" src="images/connectips.png" alt="Connect IPS">
</li>
</ul>
</section>
</main>

<!-- Footer -->
<footer class="footer">
    <p>&copy; 2024 E-Pasal. All rights reserved.</p>
</footer>
</body>

</html>