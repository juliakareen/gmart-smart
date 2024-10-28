<?php
session_start();
require '../db.php'; // Ensure you have a valid db.php file for database connection

// Check if order_id is set in the URL
if (!isset($_GET['order_id'])) {
    header("Location: customer_dashboard.php"); // Redirect to customer dashboard if no order_id is provided
    exit();
}

// Get the order ID from the URL
$order_id = $_GET['order_id'];

// Fetch order details
$order_query = "SELECT o.order_id, o.total_amount, o.order_date, os.status
                FROM orders o
                JOIN order_status os ON o.order_id = os.order_id
                WHERE o.order_id = ?";
$stmt = $conn->prepare($order_query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows > 0) {
    $order = $order_result->fetch_assoc();
} else {
    echo "Order not found.";
    exit();
}

// Fetch the ordered items
$items_query = "SELECT oi.quantity, oi.price, p.product_name
                FROM order_items oi
                JOIN products p ON oi.product_id = p.product_id
                WHERE oi.order_id = ?";
$stmt = $conn->prepare($items_query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5dc;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding-top: 100px;
        }

        header {
            background-color: #8bca84;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .logo {
            max-width: 50px;
            margin-left: 10px;
        }

        .header-title {
            font-size: 1.8em;
            color: white;
            text-align: center;
            flex-grow: 1;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }


        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            width: 90%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
        }

        h1, h2 {
            color: #333;
            text-align: center;
            font-size: 1.5em;
        }

        p, table {
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
            font-size: 1.2em;
            text-align: right;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            text-align: center;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn:hover {
            background-color: #2980b9;
            transform: translateY(-3px);
        }

        .alert {
            background-color: #e7f3fe;
            color: #31708f;
            border: 1px solid #bce8f1;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .header-title {
                font-size: 1.3em;
            }
            
            .logo {
                max-width: 40px;
            }
            
            .container {
                width: 95%;
                padding: 15px;
            }
            
            h1, h2 {
                font-size: 1.3em;
            }

            .btn {
                width: 100%;
                padding: 12px;
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
<header>
    <img src="../assets/images/website/logo.jpg" alt="Logo" class="logo">
    <div class="header-title">Order Confirmation</div>
</header>

    <div class="container">
        <h1>Thank You for Your Order!</h1>
        <p style="color: blue; text-align: center;">Please wait for your orders to "Ready to Pickup" status!</p>
        
        <div class="alert">
            <strong>Notice:</strong> Please pay at the counter when you pick up your order. Show your receipt to the staff for processing.
        </div>

        <h2>Order Details</h2>
        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
        <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
        <p><strong>Order Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>

        <h2>Items Ordered</h2>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $items_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td>RM <?php echo htmlspecialchars(number_format($item['price'], 2)); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p class="total">Total Amount: RM <?php echo htmlspecialchars(number_format($order['total_amount'], 2)); ?></p>

        <a href="explore.php" class="btn">Back to Dashboard</a>
    </div>
</body>
</html>
