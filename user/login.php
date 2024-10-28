<?php
session_start();
include '../db.php';

$message = ""; // Initialize the message variable for error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $query = "SELECT * FROM customers WHERE username=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username); // Bind the parameter
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['customer_logged_in'] = true;
            $_SESSION['user_id'] = $row['customer_id']; // Ensure user ID is set correctly
            error_log("Login successful. User ID: " . $row['customer_id']); // Debugging line to check session storage
            header("Location: explore.php"); // Redirect to explore page after login
            exit();
        } else {
            $message = "Invalid password"; // Set message for invalid password
        }
    } else {
        $message = "Invalid username"; // Set message for invalid username
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>User Login</title>
    <style>
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
        }

        h1 {
            color: #fff; /* Change header text color to white */
            margin: 0; /* Remove default margin */
            margin-left: 10px; /* Space between logo and title */
            font-size: 24px; /* Increase font size */
        }

        main {
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent background for readability */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Stronger shadow for form */
            width: 300px; /* Set a fixed width for the form */
            transition: transform 0.3s; /* Smooth transition for hover effect */
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
            width: 93%; /* Full width */
            padding: 10px;
            margin: 5px 0 20px; /* Space between inputs */
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
            padding: 8px; /* Smaller padding for a smaller button */
            border-radius: 5px;
            cursor: pointer;
            width: 65%; /* Set a smaller width for the button */
            font-size: 14px; /* Adjust font size */
            margin: 0 auto; /* Center the button */
            display: block; /* Make it a block element to center */
            transition: background-color 0.3s, box-shadow 0.3s; /* Transition for button */
        }

        button:hover {
            background-color: #0056b3; /* Darker blue on hover */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); /* Shadow on hover */
        }

        p {
            color: red;
            text-align: center; /* Center the error message */
            margin-top: 10px; /* Add space above error message */
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
            color: #007BFF; /* Link color */
        }

        .back-link:hover {
            text-decoration: underline; /* Underline on hover */
        }

        /* Center the Register link */
        .register-link {
            display: block; /* Block element */
            text-align: center; /* Center the text */
            margin-top: 10px; /* Space above the link */
            text-decoration: none; /* Remove underline */
            color: #007BFF; /* Link color */
            transition: color 0.3s; /* Transition for register link */
        }

        .register-link:hover {
            text-decoration: underline; /* Underline on hover */
            color: #0056b3; /* Darker blue on hover */
        }

        /* Logo styling */
        .logo {
            width: 80px; /* Set a larger width for the logo */
            height: auto; /* Maintain aspect ratio */
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
            <span style="font-size: 36px; margin-left: 20px; vertical-align: middle;">User Login</span>
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
        <a class="register-link" href="register.php">Register Here</a> <!-- Centered Register link -->
        <?php if (!empty($message)) : ?>
            <p><?php echo $message; ?></p> <!-- Display error message if exists -->
        <?php endif; ?>
        <a class="back-link" href="../index.php">Back</a> <!-- Back to Index link -->
    </main>
</body>
</html>
