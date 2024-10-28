<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
include '../db.php';

// Fetch all users from the database
$query = "SELECT customer_id, username, email FROM customers ORDER BY customer_id ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #E7F9E7; /* Change background color to #E7F9E7 */
            margin: 0;
            color: #333; /* Default text color */
        }

        .header {
            background-color: #007BFF; /* Set header background color */
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            text-align: center; /* Center the header text */
        }
        
        .header img {
            max-width: 90px; /* Adjust the logo size */
            position: absolute; /* Position logo on the left */
            left: 20px; /* Adjust distance from the left */
            top: 20px; /* Adjust distance from the top */
        }

        .header h1 {
            margin: 0;
            color: #333; /* Change header title color */
        }

        table {
            width: 80%; /* Set the desired width for the table */
            max-width: 600px; /* Optional: Set a maximum width */
            border-collapse: collapse; /* Ensure borders are collapsed */
            background-color: white; /* Set table background to white */
            margin: 50px auto; /* Center the table and add space above */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Add shadow for better appearance */
            border-radius: 8px; /* Optional: Add rounded corners to the table */
        }


        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .action-links a {
            margin-right: 10px;
        }

        .delete-btn {
            color: red;
            cursor: pointer;
        }

        .back-button {
            margin-top: 20px;
            text-align: center; /* Center the link */
        }

        .back-button a {
            color: #007BFF; /* Link color */
            text-decoration: underline; /* Underline style */
            font-size: 16px; /* Font size */
        }

        .back-button a:hover {
            color: #0056b3; /* Darker color on hover */
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function deleteUser(customer_id) {
            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: 'delete_user.php',
                    type: 'POST',
                    data: { customer_id: customer_id },
                    success: function(response) {
                        if (response == 'success') {
                            // Remove the user row from the table
                            $('#user_' + customer_id).remove();
                        } else {
                            alert('Error: Unable to delete user.');
                        }
                    }
                });
            }
        }
    </script>
</head>
<body>
    <header class="header">
        <img src="../assets/images/website/logo.jpg" alt="Website Logo">
        <h1>Manage Users</h1>
    </header>
    
    <!-- Display the list of users -->
    <table>
        <thead>
            <tr>
                <th>Customer ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr id="user_<?php echo $row['customer_id']; ?>">
                        <td><?php echo $row['customer_id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td class="action-links"> 
                            <span class="delete-btn" onclick="deleteUser(<?php echo $row['customer_id']; ?>)">Delete</span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

   <!-- Back to Dashboard Link -->
    <div class="back-button">
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
