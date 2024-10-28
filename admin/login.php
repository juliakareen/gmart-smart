<?php
session_start();
include '../db.php'; // Include database connection

// Initialize variables
$message = "";

// Check if the admin is already logged in
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php"); // Redirect to the dashboard
    exit();
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check the admin credentials
    $query = "SELECT * FROM admin WHERE username = ?"; // Use prepared statements to avoid SQL injection
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username); // Bind the username parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();

        // Check if the password matches (assuming passwords are stored in plain text; if hashed, use password_verify)
        if ($password == $admin['password']) {
            // Password matches, set session variables
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username']; // Store username in session
            $_SESSION['admin_id'] = $admin['admin_id']; // Store admin ID in session
            header("Location: dashboard.php"); // Redirect to the admin dashboard
            exit();
        } else {
            $message = "Invalid username or password."; // Incorrect password
        }
    } else {
        $message = "Invalid username or password."; // Incorrect username
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Admin Login</title>
    <style>
        /* Styles remain the same */
        body {
            font-family: Arial, sans-serif;
            background-image: url('../assets/images/Website/stall.jpg'); /* Set the background image */
            background-size: cover; /* Cover the entire page */
            background-position: center; /* Center the background image */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
            color: white; /* Change text color to white for better contrast */
            animation: fadeIn 1s; /* Fade-in effect for the body */
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #007BFF; /* Set header background color to blue */
            padding: 10px; /* Add some padding for better appearance */
            border-radius: 5px; /* Add slight rounding to corners */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5); /* Shadow for depth */
        }

        h1 {
            color: #fff; /* Change text color to white */
            margin: 0;
            margin-left: 10px;
            font-size: 24px;
        }

        main {
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent background for readability */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Stronger shadow for form */
            width: 300px;
            transition: transform 0.3s ease; /* Smooth transition for hover effect */
        }

        main:hover {
            transform: scale(1.02); /* Slightly enlarge on hover */
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 93%;
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            transition: border 0.3s; /* Transition for input border */
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border: 1px solid #007BFF; /* Highlight border on focus */
            outline: none; /* Remove default outline */
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 8px;
            border-radius: 5px;
            cursor: pointer;
            width: 65%;
            font-size: 14px;
            margin: 0 auto;
            display: block;
            transition: background-color 0.3s; /* Transition for button */
        }

        button:hover {
            background-color: #0056b3; /* Darker blue on hover */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); /* Shadow on hover */
        }

        p {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
            color: #007BFF;
            transition: color 0.3s; /* Transition for back link */
        }

        .back-link:hover {
            text-decoration: underline;
            color: #0056b3; /* Darker blue on hover */
        }

        .logo {
            width: 80px;
            height: auto;
        }
    </style>
</head>
<body>
    <header style="text-align: center;">
        <h1 style="display: inline-block; white-space: nowrap;">
            <!-- Logo with increased size using !important -->
            <img src="../assets/images/website/logo.jpg" class="logo" alt="Gmart Smart Logo" 
                style="width: 100px !important; height: auto !important; vertical-align: middle;">
                
            <!-- Welcome message aligned with the logo -->
            <span style="font-size: 36px; margin-left: 20px; vertical-align: middle;">Admin Login</span>
        </h1>
    </header>
    <main>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <?php if (!empty($message)) : ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <a class="back-link" href="../index.php">Back</a>
    </main>
</body>
</html>
