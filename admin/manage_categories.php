<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
include '../db.php';

$error_message = ""; // Initialize an error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];

    // Check if the category already exists
    $check_query = "SELECT * FROM categories WHERE category_name = '$name'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $error_message = "Category already exists. Please choose a different name."; // Set the error message
    } else {
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($_FILES['image']['name']);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $image_size = $_FILES['image']['size'];

            // Set size limit (5MB for example)
            $max_file_size = 5 * 1024 * 1024; // 5 MB

            // Allow only certain file formats
            $allowed_types = array("jpg", "png", "jpeg", "gif");

            // Check file size
            if ($image_size > $max_file_size) {
                $error_message = "File size must be less than 5 MB.";
            } elseif (!in_array($imageFileType, $allowed_types)) {
                $error_message = "Only JPG, JPEG, PNG & GIF files are allowed.";
            } else {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    // Insert category name and image path into the database
                    $query = "INSERT INTO categories (category_name, image) VALUES ('$name', '$target_file')";
                    $conn->query($query);
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Categories</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #E7F9E7; /* Changed to solid background color */
            margin: 0;
            color: #333; /* Default text color */
        }

        .header {
            display: flex;
            align-items: center; /* Align logo and title vertically */
            background-color: rgba(0, 123, 255, 0.8); /* Semi-transparent header color */
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 0; /* No border-radius for full-width */
            width: 100%; /* Make header cover full width */
            position: relative; /* Position relative for absolute elements */
        }

        .header img {
            height: auto; /* Maintain aspect ratio */
            max-height: 80px; /* Set a max height for the logo */
            width: auto; /* Maintain aspect ratio */
            margin-right: 20px; /* Space between logo and title */
        }

        h1 {
            color: #333;
            margin-left: 10px;
            font-size: 50px; 
            font-weight: 200;
            text-align: center; /* Center the header text */
            flex: 1; /* Allow the h1 to take available space */
        }

        .container {
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center content vertically */
            align-items: center; /* Center content horizontally */
            min-height: 80vh; /* Height for center alignment */
            padding: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center; /* Center form contents */
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin: 20px 0; /* Add some vertical margin */
        }

        input[type="text"],
        input[type="file"] {
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
            color: #333; /* Change to white color */
        }

        ul {
            list-style-type: none;
            padding: 0;
            display: flex; /* Use flexbox to arrange items in a row */
            flex-wrap: wrap; /* Allow wrapping to the next line if necessary */
            justify-content: center; /* Center items */
            max-width: 100%; /* Adjust to the full width */
            margin: 20px 0; /* Add some vertical margin */
        }

        li {
            background-color: #333;
            padding: 20px;
            margin: 10px; /* Adjust margin to create space between items */
            border-radius: 12px;
            display: flex;
            flex-direction: column; /* Align items in a column */
            justify-content: center; /* Center the contents */
            align-items: center; /* Center contents */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            width: 150px; /* Set a fixed width for the list items */
            transition: background-color 0.3s, transform 0.3s; /* Transition for background and transform */
        }

        li:hover {
            background-color: #007BFF; /* Change background color on hover */
            transform: scale(1.05); /* Slightly enlarge item on hover */
        }

        li img {
            width: 50px;
            height: 40px;
            object-fit: cover;
            border-radius: 5px;
            margin-left: 10px;
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
            text-decoration: none;
            font-weight: 600;
            margin: 10px 0; /* Add margin for spacing */
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
        <img src="../assets/images/website/logo.jpg" alt="Logo"> <!-- Inserted logo on the left -->
        <h1>Manage Categories</h1>
    </div>

    <div class="container">
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Category Name" required>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit">Add Category</button>
        </form>

        <a href="dashboard.php" class="blue-link">Back to Dashboard</a> <!-- Link before category list -->

        <h2>Category List</h2>
        <ul>
            <?php
            $result = $conn->query("SELECT * FROM categories");
            while ($row = $result->fetch_assoc()) {
                echo "<li><a href='category_details.php?id=" . $row['category_id'] . "'>" . htmlspecialchars($row['category_name']) . "</a>"; // Make category name clickable
                if (!empty($row['image'])) {
                    echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['category_name']) . "'>";
                }
                echo "</li>";
            }
            ?>
        </ul>

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
