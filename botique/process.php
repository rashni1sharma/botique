<?php
header('Content-Type: application/json');

// 1. Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'error' => 'Method not allowed']));
}

// 2. Configure database
$db = new mysqli('localhost', 'root', '', 'botique');
if ($db->connect_error) {
    http_response_code(500);
    die(json_encode(['success' => false, 'error' => 'Database connection failed']));
}

// 3. Validate and sanitize inputs
$required = ['username', 'email', 'phone', 'address', 'size', 'rentalDays'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        http_response_code(400);
        die(json_encode(['success' => false, 'error' => "$field is required"]));
    }
}

// 4. Process file uploads
$uploads = [
    'citizenship' => $_FILES['citizenshipFile'] ?? null,
    'payment' => $_FILES['paymentFile'] ?? null
];

foreach ($uploads as $type => $file) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        die(json_encode(['success' => false, 'error' => "$type upload failed"]));
    }
}

// 5. Generate rental ID and dates
$rentalId = 'RENT-' . bin2hex(random_bytes(4));
$pickupDate = date('Y-m-d');
$returnDate = date('Y-m-d', strtotime("+{$_POST['rentalDays']} days"));

// 6. Save files
$filePaths = [];
try {
    foreach ($uploads as $type => $file) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = "$rentalId-$type.$ext";
        $path = "uploads/$filename";
        
        if (!move_uploaded_file($file['tmp_name'], $path)) {
            throw new Exception("Failed to save $type file");
        }
        $filePaths[$type] = $filename;
    }

    // 7. Insert into database
    $stmt = $db->prepare("INSERT INTO rentals (
        rental_id, customer_name, customer_email, customer_phone, customer_address,
        clothing_size, rental_days, citizenship_file, payment_file, pickup_date, return_date
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param(
        'ssssssissss',
        $rentalId,
        $_POST['username'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['address'],
        $_POST['size'],
        $_POST['rentalDays'],
        $filePaths['citizenship'],
        $filePaths['payment'],
        $pickupDate,
        $returnDate
    );

    if (!$stmt->execute()) {
        throw new Exception('Database save failed');
    }

    // 8. Return success
    echo json_encode([
        'success' => true,
        'rentalId' => $rentalId,
        'pickupDate' => $pickupDate,
        'returnDate' => $returnDate,
        'email' => $_POST['email']
    ]);

} catch (Exception $e) {
    // Clean up any uploaded files on error
    foreach ($filePaths as $path) {
        @unlink("uploads/$path");
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$db->close();
?>