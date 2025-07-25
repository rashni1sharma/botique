<?php
include 'dbconfig.php';

// eSewa sends a response with parameters encoded in Base64
if (isset($_REQUEST['data'])) {
    $response_base64 = $_REQUEST['data'];
    $response_json = base64_decode($response_base64);
    $response = json_decode($response_json, true);

    // Extract the relevant fields from the response
    $transaction_code = $response['transaction_code'];
    $status = $response['status'];
    $total_amount = $response['total_amount'];
    $transaction_uuid = $response['transaction_uuid'];
    $product_code = $response['product_code'];
    $signed_field_names = $response['signed_field_names'];
    $provided_signature = $response['signature'];

    // Secret Key provided by eSewa
    $secret_key = "8gBm/:&EnhH.1/q";

    // Create the message string by concatenating the signed field names and their values
    $message = "transaction_code={$transaction_code},status={$status},total_amount={$total_amount},transaction_uuid={$transaction_uuid},product_code={$product_code},signed_field_names={$signed_field_names}";

    // Generate the expected signature
    $expected_signature = base64_encode(hash_hmac('sha256', $message, $secret_key, true));

    // Compare the generated signature with the provided signature
    if ($expected_signature === $provided_signature) {
        // Signature matches, proceed with processing
        echo "Payment verification successful. Transaction Code: " . $transaction_code . "<br>";

        // Update the order status to 'complete' and set updated_at timestamp
        $updated_at = date('Y-m-d H:i:s');


        // Update the order status in the database
        $sql = "UPDATE orders SET status = '$status', updated_at = '$updated_at' WHERE invoice_no = '$transaction_uuid'";
        if ($conn->query($sql) === TRUE) {
            echo "Order status updated successfully.";
        } else {
            echo "Error updating order status: " . $conn->error;
        }
    } else {
        // Signature does not match, potential tampering
        echo "Error: Signature mismatch. Payment verification failed.";
    }
} else {
    echo "Invalid request!";
    exit();
}

$conn->close();
?>

<a href="/EsewaIntegration">Back to Home Page</a>