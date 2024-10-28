<?php
session_start();
require '../db.php'; // Ensure db.php is correctly configured

$message = '';
$orders = []; // To store orders details

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if the user is not logged in
    exit();
}

// Get the logged-in customer's ID from the session
$customer_id = $_SESSION['user_id'];

// Fetch all orders for the logged-in user
$order_query = "SELECT order_id, order_status, order_date FROM orders WHERE customer_id = ?";
$stmt = $conn->prepare($order_query);
if ($stmt) {
    $stmt->bind_param('i', $customer_id);
    $stmt->execute();
    $stmt->bind_result($order_id, $order_status, $order_date);

    while ($stmt->fetch()) {
        $orders[] = [
            'order_id' => $order_id,
            'order_status' => $order_status,
            'order_date' => $order_date
        ];
    }
    $stmt->close();
} else {
    $message = "Error preparing statement: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5dc; /* Updated background color */
            color: #333;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: center; /* Center the header */
            background-color: #8bca84; /* Header color */
            padding: 10px;
            position: relative; /* Enable positioning for logo */
        }
        .header img {
            position: absolute; /* Position logo to the left */
            left: 20px; /* Space from the left */
            height: 50px; /* Set logo height */
        }
        h1 {
            color: white; /* Change header text color */
            margin: 0; /* Remove default margin */
        }
        .message {
            color: red;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid red;
            border-radius: 5px;
            background-color: #fce4e4;
            display: <?php echo $message ? 'block' : 'none'; ?>; /* Show if message is set */
        }
        .order-container {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .order-item {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        .order-item:last-child {
            border-bottom: none; /* Remove last border */
        }
        .cancel-btn {
            display: inline-block;
            padding: 5px 10px;
            background-color: #e74c3c;
            color: white;
            border-radius: 3px;
            text-decoration: none;
        }
        .cancel-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="../assets/images/website/logo.jpg" alt="Logo"> <!-- Logo on the left -->
        <h1>Your Orders</h1>
    </div>

    <div class="message"><?php echo htmlspecialchars($message); ?></div>

    <?php if (!empty($orders)): ?>
        <div class="order-container">
            <?php foreach ($orders as $order): ?>
                <div class="order-item">
                    <strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?><br>
                    <strong>Status:</strong> <?php echo htmlspecialchars($order['order_status']); ?><br>
                    <strong>Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?>

                    <?php if ($order['order_status'] == 'Pending' || $order['order_status'] == 'Processing'): ?>
                        <a href="cancel_order.php?order_id=<?php echo htmlspecialchars($order['order_id']); ?>" class="cancel-btn" style="margin-left: 320px;">Cancel Order</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="order-container">
            <p>No orders found for your account.</p>
        </div>
    <?php endif; ?>
    
    <p style="text-align: center; margin-top: 20px;">
        <a href="explore.php" style="text-decoration: underline; color: blue;">Back to Dashboard</a>
    </p>
</body>
</html>
