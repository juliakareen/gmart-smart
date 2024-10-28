<?php
session_start();
require '../db.php'; // Ensure this path is correct for your db connection

// Initialize cart array if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to calculate total price
function calculateTotal($cart) {
    global $conn;
    $total = 0;
    foreach ($cart as $product_id => $quantity) {
        $query = "SELECT price FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $total += $row['price'] * $quantity;
        }
    }
    return $total;
}

// Update quantity in cart
if (isset($_GET['update_quantity']) && isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $action = $_GET['update_quantity'];

    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 0;
    }

    $query = "SELECT quantity_in_stock FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    if ($product) {
        $stock = $product['quantity_in_stock'];

        if ($action == 'increase' && $_SESSION['cart'][$product_id] < $stock) {
            $_SESSION['cart'][$product_id]++;
        } elseif ($action == 'decrease' && $_SESSION['cart'][$product_id] > 1) {
            $_SESSION['cart'][$product_id]--;
        }
    } else {
        echo "Product not found.";
    }
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Calculate total
$total = calculateTotal($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-color: #f5f5dc;
            overflow-y: auto;
        }

        header {
            background-color: #8bca84;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .logo {
            max-width: 60px;
            margin-right: 10px;
        }

        .header-title {
            flex-grow: 1;
            text-align: center;
            font-size: 1.5em;
            color: white;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        main {
            flex: 1;
            padding: 20px;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: left;
            word-wrap: break-word;
        }

        th {
            background-color: #f2f2f2;
        }

        .remove-button {
            color: #e74c3c;
            text-decoration: none;
            transition: color 0.3s;
        }

        .remove-button:hover {
            text-decoration: underline;
            color: #c0392b;
        }

        .total {
            font-weight: bold;
            font-size: 1.5em;
            text-align: center;
            margin: 20px 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 10px;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
        }

        .quantity-control {
            display: inline-flex;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }

        .quantity-control a {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            font-size: 20px;
            text-decoration: none;
            color: #333;
            background-color: #f9f9f9;
            border: none;
            outline: none;
            cursor: pointer;
            border-left: 1px solid #ddd;
            transition: background-color 0.3s;
        }

        .quantity-control a:hover {
            background-color: #e0e0e0;
        }

        .quantity-control .quantity-value {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 50px;
            font-size: 18px;
            color: #d32f2f;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            background-color: white;
        }

        @media (max-width: 768px) {
            header {
                padding: 10px;
                text-align: center;
            }

            .logo {
                max-width: 40px;
                margin-right: 5px;
            }

            .header-title {
                font-size: 1.2em;
            }

            main {
                padding: 10px;
            }

            table, th, td {
                font-size: 0.9em;
                padding: 8px;
            }

            .quantity-control a {
                width: 30px;
                height: 30px;
                font-size: 16px;
            }

            .quantity-control .quantity-value {
                width: 40px;
                font-size: 16px;
            }

            .btn {
                font-size: 0.9em;
                padding: 8px 12px;
            }
        }
    </style>
</head>
<body>
    <header>
        <img src="../assets/images/website/logo.jpg" alt="Logo" class="logo">
        <div class="header-title">Your Shopping Cart</div>
    </header>
    <main>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($_SESSION['cart'])): ?>
                    <tr>
                        <td colspan="5">Your cart is empty.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($_SESSION['cart'] as $product_id => $quantity): ?>
                        <?php
                        $query = "SELECT product_name, price, quantity_in_stock FROM products WHERE product_id = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $product_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $product = $result->fetch_assoc();
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td>
                                <div class="quantity-control">
                                    <a href="review_cart.php?update_quantity=decrease&product_id=<?php echo $product_id; ?>" class="quantity-button">-</a>
                                    <div class="quantity-value"><?php echo htmlspecialchars($quantity); ?></div>
                                    <a href="review_cart.php?update_quantity=increase&product_id=<?php echo $product_id; ?>" class="quantity-button">+</a>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($product['quantity_in_stock']); ?></td>
                            <td>RM <?php echo htmlspecialchars($product['price']); ?></td>
                            <td><a href="review_cart.php?remove=<?php echo $product_id; ?>" class="remove-button">Remove</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="total">
            <h2>Total: RM <?php echo htmlspecialchars($total); ?></h2>
        </div>
        <div class="actions">
            <a href="checkout.php" class="btn">Proceed to Checkout</a>
            <a href="explore.php" class="btn">Continue Shopping</a>
        </div>
        <p style="text-align: center; margin-top: 20px;">
            <a href="explore.php" style="text-decoration: underline; color: blue; float: left;">Back to Dashboard</a>
        </p>
    </main>
    <footer>
        <p>&copy; 2024 Gmart Smart. All rights reserved.</p>
    </footer>
</body>
</html>
