<?php
session_start();
include '../db.php';

$message = ""; // Initialize the message variable for error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Password validation regex: at least one uppercase letter, one lowercase letter, one digit, and one special character
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', $password)) {
        $message = "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
    } else {
        // Check if the username already exists
        $check_query = $conn->prepare("SELECT * FROM customers WHERE username = ?");
        $check_query->bind_param("s", $username);
        $check_query->execute();
        $check_result = $check_query->get_result();

        if ($check_result->num_rows > 0) {
            $message = "Username already taken. Please choose another one."; // Username exists
        } else {
            // Prepare the insert query
            $insert_query = $conn->prepare("INSERT INTO customers (username, email, password) VALUES (?, ?, ?)");
            // Hash the password before storing it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_query->bind_param("sss", $username, $email, $hashed_password);

            if ($insert_query->execute()) {
                $message = "Registration successful! You can now log in."; // Successful registration
            } else {
                $message = "Error during registration: " . $conn->error; // Database error
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
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
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 300px; /* Set a fixed width for the form */
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 93%; /* Full width */
            padding: 10px;
            margin: 5px 0 20px; /* Space between inputs */
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
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
        }

        button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        p {
            color: red;
            text-align: center; /* Center the error message */
            margin-top: 10px; /* Add space above error message */
        }

        .login-link, 
        .back-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
            color: #007BFF; /* Link color */
        }

        .login-link:hover,
        .back-link:hover  {
            text-decoration: underline; /* Underline on hover */
        }

        /* Logo styling */
        .logo {
            width: 80px; /* Set a larger width for the logo */
            height: auto; /* Maintain aspect ratio */
        }
    </style>
    <script>
        // Function to hide the message after 5 seconds
        function hideMessage() {
            const messageElement = document.getElementById('message');
            if (messageElement) {
                setTimeout(() => {
                    messageElement.style.display = 'none';
                }, 5000); // 5000 milliseconds = 5 seconds
            }
        }

        // Call hideMessage if the message exists
        window.onload = hideMessage;
    </script>
</head>
<body>
<header style="text-align: center;">
        <h1 style="display: inline-block; white-space: nowrap;">
            <!-- Logo with increased size using !important -->
            <img src="../assets/images/website/logo.jpg" class="logo" alt="Gmart Smart Logo" 
                style="width: 100px !important; height: auto !important; vertical-align: middle;">
                
            <!-- Welcome message aligned with the logo -->
            <span style="font-size: 36px; margin-left: 20px; vertical-align: middle;">User Register</span>
        </h1>
    </header>
    <main>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Register</button>
        </form>
        <?php if (!empty($message)) : ?>
            <p id="message"><?php echo $message; ?></p> <!-- Display error message if exists -->
        <?php endif; ?>
        <a class="back-link" href="../index.php">Back</a> <!-- Back to Index link -->
        <a class="login-link" href="login.php">Login</a> <!-- Link to Login page -->
    </main>
</body>
</html>
