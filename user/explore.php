<?php
session_start();
include '../db.php';

if (!isset($_SESSION['customer_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Fetch categories for sidebar
$categories_result = $conn->query("SELECT * FROM categories");

// Handle category selection and product display
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';

// Fetch products based on selected category
if (!empty($category_id)) {
    $products_result = $conn->query("SELECT * FROM products WHERE category_id = " . $category_id);
} else {
    $products_result = $conn->query("SELECT * FROM products"); // Fetch all products if no category is selected
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GMART SMART</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5dc;
        }
        header {
            background-color: #8bca84;
            padding: 30px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }
        .logo {
            width: 65px;
            height: auto;
            margin-right: 10px;
        }
        .header-title {
            font-size: 32px;
            font-weight: bold;
            color: white;
        }
        nav {
            position: relative;
        }
        .menu {
            cursor: pointer;
            background-color: #f0f0f0;
            color: black;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            position: relative;
        }

        .dropdown {
            display: none;
            position: absolute;
            background-color: white;
            color: black;
            min-width: 140px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 4px;
            overflow: visible;
            right:0px;
            top: 40px;
        }

        .dropdown.show {
            display: block;
        }

        .dropdown a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown a:hover {
            background-color: #f1f1f1;
        }
        .search-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .search-container input {
            width: 60%;
            padding: 12px;
            font-size: 16px;
            border: 2px solid #0056b3;
            border-radius: 25px;
            outline: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .container {
            display: flex;
            max-width: 1200px;
            margin: 20px auto;
        }

        .sidebar {
            background-color: #bfe3b4;
            padding: 15px;
            border-radius: 5px;
            width: 200px;
        }

        .sidebar h2 {
            color: white;
            text-align: center;
        }

        .sidebar a {
            display: block;
            padding: 10px;
            margin: 5px 0;
            background-color: white;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }

        .sidebar a:hover {
            background-color: #ddd;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 10px;
        }

        .content {
            flex-grow: 1;
            margin-left: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
        }

        .product-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .product {
            width: calc(33.333% - 20px);
            border: 1px solid #e0e0e0;
            padding: 15px;
            border-radius: 5px;
            background: #fafafa;
        }

        .product img {
            max-width: 100px;
            height: auto;
            margin-bottom: 10px;
        }

        .add-to-cart {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        .add-to-cart:hover {
            background-color: #0056b3;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            header {
                padding: 20px 10px;
                flex-direction: column;
                text-align: center;
            }

            .logo-container {
                position: static;
                margin-bottom: 10px;
            }

            .header-title {
                font-size: 24px;
            }

            nav {
                position: static;
                margin-top: 10px;
            }

            .search-container input {
                width: 90%;
            }

            .container {
                flex-direction: column;
                padding: 10px;
            }

            .sidebar {
                width: 95%;
                margin-bottom: 20px;
            }

            .content {
                margin-left: 0;
            }

            .product-list {
                display: grid;
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .product {
                width: 100%; /* Full width for each product */
                box-sizing: border-box;
                margin: 0 auto;
            }
        }

        @media (max-width: 480px) {
            .header-title {
                font-size: 20px;
            }

            .search-container input {
                font-size: 14px;
                padding: 10px;
            }

            .add-to-cart {
                padding: 8px 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="logo-container" style="position: absolute; left: 20px;">
        <img src="../assets/images/website/logo.jpg" alt="Logo" class="logo">
    </div>
    <span class="header-title">GMART SMART</span>
    <nav style="position: absolute; right: 20px;">
        <!-- Drop-down menu -->
        <div class="menu" onclick="toggleDropdown()">Menu</div>
        <div id="dropdown" class="dropdown">
            <a href="review_cart.php">Cart</a>
            <a href="user_account.php">User Account</a>
            <a href="notification.php">Order Details</a>
            <a href="order_history.php">Order History</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>
</header>

<div class="search-container">
    <input type="text" id="search-bar" placeholder="Search for products..." oninput="filterProducts()">
</div>

<div class="container">
    <aside class="sidebar">
        <h2>Categories</h2>
        <ul>
            <li><a href="explore.php">All Categories</a></li>
            <?php while ($category = $categories_result->fetch_assoc()) : ?>
                <li><a href="explore.php?category_id=<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['category_name']); ?></a></li>
            <?php endwhile; ?>
        </ul>
    </aside>

    <main class="content">
        <h1>Products</h1>
        <div class="product-list">
            <?php if ($products_result->num_rows > 0): ?>
                <?php while ($product = $products_result->fetch_assoc()) : ?>
                    <div class="product">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                        <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        <p>Price: RM<?php echo htmlspecialchars($product['price']); ?></p>
                        <a href="add_to_cart.php?id=<?php echo $product['product_id']; ?>" class="add-to-cart">Add to Cart</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products found in this category.</p>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('dropdown');
        dropdown.classList.toggle('show');
    }

    window.onclick = function(event) {
        if (!event.target.matches('.menu')) {
            const dropdown = document.getElementById('dropdown');
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        }
    };

    function filterProducts() {
        const input = document.getElementById('search-bar');
        const filter = input.value.toLowerCase();
        const productList = document.querySelectorAll('.product');

        productList.forEach(product => {
            const productName = product.querySelector('h3').textContent.toLowerCase();
            product.style.display = productName.includes(filter) ? '' : 'none';
        });
    }
</script>

</body>
</html>
