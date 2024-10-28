<?php
session_start();
require '../db.php'; // Ensure you have a valid db.php file for database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if the user is not logged in
    exit();
}

// Get the logged-in customer's ID from the session
$customer_id = $_SESSION['user_id'];

// Fetch only completed orders for the logged-in customer
$order_query = "SELECT o.order_id, o.total_amount, o.order_date, os.status
                FROM orders o
                JOIN order_status os ON o.order_id = os.order_id
                WHERE o.customer_id = ? AND os.status = 'Complete'";
$stmt = $conn->prepare($order_query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$order_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0; /* Remove default padding */
            background-color: #f5f5dc; /* Updated background color */
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: center; /* Center the header */
            background-color: #8bca84; /* Header background color */
            padding: 10px;
            position: relative; /* Enable positioning for logo */
        }
        .header img {
            position: absolute; /* Position logo to the left */
            left: 20px; /* Space from the left */
            height: 50px; /* Set logo height */
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px; /* Increased border radius for a softer look */
            max-width: 800px;
            margin: 20px auto; /* Center the container */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: white; /* Change header text color */
            margin: 0; /* Remove default margin */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease; /* Smooth transition */
        }
        .btn:hover {
            background-color: #2980b9;
        }

        /* Media queries for responsiveness */
        @media (max-width: 768px) {
            .container {
                padding: 15px; /* Reduced padding for mobile */
            }
            h1 {
                font-size: 24px; /* Adjusted font size for mobile */
            }
            table th, table td {
                padding: 10px; /* Reduced padding for mobile */
            }
        }
        
        /* Specific styles for smaller mobile screens */
        @media (max-width: 480px) {
            .header img {
                height: 40px; /* Smaller logo for small screens */
            }
            h1 {
                font-size: 20px; /* Further adjusted font size for very small screens */
            }
            .btn {
                padding: 8px 16px; /* Smaller button on mobile */
                font-size: 14px; /* Reduced font size */
                width: auto; /* Remove full-width for a smaller button */
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="../assets/images/website/logo.jpg" alt="Logo"> <!-- Logo on the left -->
        <h1>Order History</h1>
    </div>

    <div class="container">
        <?php if ($order_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Total Amount (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $order_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($order['total_amount'], 2)); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No completed orders found for your account.</p>
        <?php endif; ?>

        <a href="explore.php" class="btn">Back to Dashboard</a>
    </div>
</body>
</html>
