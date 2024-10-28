<?php
session_start();
require '../db.php';

if (isset($_GET['order_id']) && isset($_SESSION['user_id'])) {
    $order_id = $_GET['order_id'];
    $customer_id = $_SESSION['user_id'];

    // Update the order status to 'Cancelled' only if it belongs to the logged-in user
    $cancel_query = "UPDATE orders SET order_status = 'Cancelled' WHERE order_id = ? AND customer_id = ? AND order_status IN ('Pending', 'Processing')";
    $stmt = $conn->prepare($cancel_query);
    $stmt->bind_param("ii", $order_id, $customer_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Order successfully cancelled.";
    } else {
        $_SESSION['message'] = "Error cancelling the order. Please try again.";
    }
    $stmt->close();
} else {
    $_SESSION['message'] = "Invalid request.";
}

header("Location: your_orders.php");
exit();
