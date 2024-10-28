<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
include '../db.php';

$error_message = ""; // Initialize an error message variable

// Fetch categories
$categories_result = $conn->query("SELECT * FROM categories");

// Handle product insertion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $quantity = $_POST['quantity'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $error_message = "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size (e.g., limit to 5MB)
        if ($_FILES["image"]["size"] > 5000000) {
            $error_message = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            // Do not upload the file
            echo '<script>showError();</script>';
        } else {
            // If everything is ok, try to upload file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Insert product into database
                $stmt = $conn->prepare("INSERT INTO products (product_name, price, category_id, quantity_in_stock, image) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sdiis", $name, $price, $category_id, $quantity, $target_file);
                if ($stmt->execute()) {
                    $stmt->close();
                } else {
                    $error_message = "Error: " . $stmt->error;
                }
            } else {
                $error_message = "Sorry, there was an error uploading your file.";
            }
        }
    }
}

// Fetch products
$products_result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #E7F9E7; /* Change background color to #E7F9E7 */
            margin: 0;
            color: #333; /* Default text color */
        }


        .header {
            display: flex;
            align-items: center;
            background-color: rgba(0, 123, 255, 0.8);
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            position: relative;
        }

        .header img {
            height: auto;
            max-height: 80px;
            width: auto;
            margin-right: 20px;
        }

        h1 {
            color: #333;
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
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center; /* Center form contents */
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin: 20px 0;
        }

        .custom-select {
            width: 90%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background-color: #f9f9f9;
            color: #333; /* Text color */
            appearance: none; /* Remove default arrow for customization */
            -webkit-appearance: none;
            -moz-appearance: none;
            position: relative;
        }

        /* Style the arrow icon */
        .custom-select::after {
            content: "▼"; /* Custom arrow */
            font-size: 12px;
            color: #333;
            position: absolute;
            right: 20px;
            top: calc(50% - 6px); /* Center the arrow vertically */
            pointer-events: none; /* Prevent the arrow from blocking clicks */
        }

        /* Optional: Add hover and focus effects */
        .custom-select:hover {
            background-color: #e9e9e9;
            border-color: #bbb;
        }

        .custom-select:focus {
            outline: none;
            border-color: #007bff;
        }

        input[type="text"],
        input[type="file"],
        input[type="number"],
        input[type="select"] {
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
        }

        button:hover {
            background-color: #0056b3;
        }

        h2 {
            text-align: center;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        .product-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 20px 0;
            max-width: 100%;
        }

        .product-item {
            background-color: #333;
            padding: 20px;
            margin: 10px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            width: 150px;
            transition: background-color 0.3s, transform 0.3s;
            color: #ffffff;
            text-decoration: none; /* Remove underline for links */
            text-align: center; /* Center the text */
        }

        .product-item:hover {
            background-color: #007BFF;
            transform: scale(1.05);
        }

        .product-item img {
            width: 100px; /* Adjust as needed */
            height: auto; /* Maintain aspect ratio */
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px; /* Space below image */
        }

        #error-message {
            display: none;
            color: #d9534f;
            font-size: 16px;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            width: 100%;
            max-width: 500px;
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        a {
            color: #ffffff; /* Link color */
            text-decoration: none; /* Remove underline */
        }

        a:hover {
            text-decoration: underline; /* Underline on hover */
        }

        .blue-link {
            color: #007bff; /* Blue color for the link */
        }

    </style>
    <script>
        function showError() {
            var message = document.getElementById("error-message");
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
        <h1>Manage Products</h1>
    </div>

    <div class="container">
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Product Name" required>
            <input type="number" name="price" placeholder="Price" required step="0.01" min="0">
            
            <select name="category_id" class="custom-select" required>
                <option value="">Select Category</option>
                <?php if ($categories_result->num_rows > 0): ?>
                    <?php while ($category = $categories_result->fetch_assoc()): ?>
                        <option value="<?php echo $category['category_id']; ?>">
                            <?php echo htmlspecialchars($category['category_name']); ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>

            <input type="number" name="quantity" placeholder="Initial Stock Quantity" required min="0">
            <input type="file" name="image" accept="image/*" required>
            <button type="submit">Add Product</button>
        </form>

        <a href="dashboard.php" class="blue-link">Back to Dashboard</a> <!-- Link before category list -->

        <h2>Product List</h2>
        <div class="product-list">
            <?php if ($products_result->num_rows > 0): ?>
                <?php while ($product = $products_result->fetch_assoc()): ?>
                    <a class="product-item" href="product_details.php?id=<?php echo $product['product_id']; ?>">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
                        <span><?php echo htmlspecialchars($product['product_name']); ?></span>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>

        <div id="error-message">
            <?php
            if ($error_message) {
                echo htmlspecialchars($error_message);
                echo '<script>showError();</script>';
            }
            ?>
        </div>
    </div>
</body>

</html>
