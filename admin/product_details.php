<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
include '../db.php';

$error_message = ""; // Initialize an error message variable

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    
    // Fetch product details
    $product_result = $conn->query("SELECT * FROM products WHERE product_id = $product_id");
    $product = $product_result->fetch_assoc();

    // Check if product exists
    if (!$product) {
        die("Product not found.");
    }

    // Update stock quantity
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_stock'])) {
        $new_quantity = intval($_POST['quantity']);
        
        // Update the quantity in the database
        $update_query = "UPDATE products SET quantity_in_stock = $new_quantity WHERE product_id = $product_id";
        if ($conn->query($update_query) === TRUE) {
            $success_message = "Stock updated successfully.";
        } else {
            $error_message = "Error updating stock: " . $conn->error;
        }
    }

    // Delete product
    if (isset($_POST['delete_product'])) {
        $delete_query = "DELETE FROM products WHERE product_id = $product_id";
        if ($conn->query($delete_query) === TRUE) {
            header("Location: manage_products.php"); // Redirect to the product list after deletion
            exit();
        } else {
            $error_message = "Error deleting product: " . $conn->error;
        }
    }
} else {
    die("No product ID specified.");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Product Details</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('../assets/images/Website/stall.jpg');
            background-size: cover;
            background-position: center;
            margin: 0;
            color: #333;
        }

        .header {
            display: flex;
            align-items: center;
            background-color: rgba(0, 123, 255, 0.8);
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .header img {
            height: auto;
            max-height: 80px;
            width: auto;
            margin-right: 20px;
        }

        h1 {
            color: #ffffff;
            margin-left: 15px;
            font-size: 40px;
            font-weight: 400;
            text-align: center;
            flex: 1;
        }

        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 20px auto; /* Center the container */
        }

        img {
            width: 100%; /* Responsive image */
            height: auto; /* Maintain aspect ratio */
            border-radius: 8px;
        }

        input[type="number"] {
            width: 90%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background-color: #f9f9f9;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
            width: 75%;
            margin: 5px 0;
        }

        button:hover {
            background-color: #0056b3;
        }

        #error-message,
        #success-message {
            display: none;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            width: 100%;
            max-width: 500px;
            margin: 10px 0;
        }

        #error-message {
            color: #d9534f;
            background-color: #f8d7da;
        }

        #success-message {
            color: #155724;
            background-color: #d4edda;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function showMessage(messageId) {
            var message = document.getElementById(messageId);
            message.style.display = "block";
            setTimeout(function () {
                message.style.display = "none";
            }, 5000);
        }
    </script>
</head>

<body>
    <div class="header">
        <img src="../assets/images/website/logo.jpg" alt="Logo">
        <h1>Product Details</h1>
    </div>

    <div class="container">
        <h2><?php echo htmlspecialchars($product['product_name']); ?></h2>
        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
        <p><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
        <p><strong>Category ID:</strong> <?php echo htmlspecialchars($product['category_id']); ?></p>
        <p><strong>Quantity in Stock:</strong> <?php echo htmlspecialchars($product['quantity_in_stock']); ?></p>

        <form method="POST" action="">
            <input type="number" name="quantity" placeholder="New Stock Quantity" required>
            <button type="submit" name="update_stock">Update Stock</button>
            <button type="submit" name="delete_product" style="background-color: red;">Delete Product</button>
        </form>

        <div id="error-message">
            <?php
            if ($error_message) {
                echo htmlspecialchars($error_message);
                echo '<script>showMessage("error-message");</script>';
            }
            ?>
        </div>
        <div id="success-message">
            <?php
            if (isset($success_message)) {
                echo htmlspecialchars($success_message);
                echo '<script>showMessage("success-message");</script>';
            }
            ?>
        </div>
        
        <a href="manage_products.php">Back to Product List</a>
    </div>
</body>

</html>
