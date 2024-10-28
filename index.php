<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"> <!-- Font Awesome CDN -->
    <title>Welcome to Gmart Smart</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('assets/images/Website/stall.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            flex-direction: column;
            height: 100vh; /* Full viewport height */
        }

        /* Header Styles */
        header {
            background-color: rgba(51, 51, 51, 0.8); /* Semi-transparent background */
            color: white;
            height: 80px; /* Reduced height for header */
            width: 100%; /* Full width for the header */
            display: flex; /* Use flexbox for alignment */
            align-items: center; /* Center vertically */
            justify-content: center; /* Center horizontally */
            z-index: 100; /* Keep it above other elements */
        }

        header h1 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 2.5em; /* Adjusted font size */
        }

        header img.logo {
            width: 150px; /* Increased logo size */
            height: auto;
            transition: transform 0.3s ease;
        }

        header img.logo:hover {
            transform: scale(1.1);
        }

        /* Navigation Styles */
        nav {
            margin: 20px auto;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .nav-section {
            padding: 5px; /* Decreased padding */
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            min-width: 100px; /* Optional: ensure a minimum width */
        }

        .nav-section:hover {
            transform: translateY(-8px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .nav-section h2 {
            font-size: 1.2em; /* Further reduced section title size */
            color: #333;
            margin-bottom: 5px; /* Reduced margin */
        }

        .nav-section ul {
            list-style: none;
            padding: 0;
        }

        .nav-section a {
            display: block;
            margin: 2px 0; /* Reduced margin */
            padding: 5px 10px; /* Decreased button padding */
            background-color: #FFD700;
            color: #333;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .nav-section a:hover {
            background-color: #050505;
            color: white;
        }

        /* Main and Section Styles */
        main {
            flex: 1; /* Take up remaining space */
            padding: 20px;
            text-align: center;
            overflow-y: auto; /* Allow scrolling if content exceeds view */
        }

        /* Flex container for About Us and Contact Info */
        .info-sections {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 0; /* Adjusted margin */
            padding: 0; /* Adjusted padding */
        }

        .info-section {
            width: 200px;
            padding: 20px;
            border-radius: 10px;
            transition: box-shadow 0.3s ease;
        }

        .info-section:hover {
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
        }

        .info-section h2 {
            font-size: 1.8em;
            color: #333;
            margin-bottom: 10px;
        }

        .info-section p {
            font-size: 1.1em;
            color: blue;
            line-height: 1.6;
        }

        /* Footer Styles */
        footer {
            background-color: rgba(51, 51, 51, 0.8);
            color: white;
            text-align: center;
            padding: 10px; /* Reduced padding */
            width: 100%;
        }

        /* Updated Map Styles */
        .map {
            width: 130%; /* Set the width to 130% */
            height: 150px; /* Keep the height as is */
            border: none;
            margin: 30px -15% auto; /* Center the map horizontally by offsetting the margin */
            display: block; /* Ensure it behaves as a block element */
        }

        footer p {
            margin: 15px 0;
            color: white;
        }

        /* Mobile-specific styles */
        @media (max-width: 768px) {
            header {
                padding: 15px;
            }

            header img.logo {
                width: 80px;
                margin-bottom: 5px;
            }

            header h1 {
                font-size: 1.2em;
                margin-bottom: 20px;
            }

            .info-sections {
                flex-direction: column;
                align-items: center;
                width: 100%;
                padding: 0;
                gap: 10px;
            }

            .info-section {
                width: 90%;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <header style="text-align: center;">
        <h1 style="display: inline-block; white-space: nowrap;">
            <!-- Logo with increased size using !important -->
            <img src="assets/images/website/logo.jpg" class="logo" alt="Gmart Smart Logo" 
                style="width: 100px !important; height: auto !important; vertical-align: middle;">
                
            <!-- Welcome message aligned with the logo -->
            <span style="font-size: 36px; margin-left: 20px; vertical-align: middle;">Welcome to Gmart Smart</span>
        </h1>
    </header>

    <!-- Navigation Section -->
    <nav>
        <div class="nav-section">
            <h2>Admin</h2>
            <ul>
                <li><a href="admin/login.php">Admin</a></li>
            </ul>
        </div>
        <div class="nav-section">
            <h2>User</h2>
            <ul>
                <li><a href="user/login.php">User</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content Section -->
    <main>
        <div class="info-sections">
            <!-- About Us Section -->
            <section class="info-section">
                <h2>About Us</h2>
                <p>Gmart Smart is your one-stop shop for all your grocery needs.</p>
            </section>

            <!-- Contact Info Section -->
            <section class="info-section">
                <h2>Contact Us</h2>
                <p>
                    <i class="fab fa-whatsapp"></i>
                    <strong>Phone:</strong> <a href="https://wa.me/60162681540" style="color: blue;">0162681540</a>
                </p>
            </section>
        </div>

        <!-- Map Section -->
        <div>
            <iframe 
                class="map"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.4750525499558!2d110.15308097422175!3d1.4868068611218765!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31fb0dec13498e1b%3A0xf2e14f78323c35f6!2sJalan%20Kg%20Sudoh%20-%20Kg%20Apar%2C%2094000%20Bau%2C%20Sarawak!5e0!3m2!1sen!2smy!4vXXXXXXXXXXXXXX!5m2!1sen!2smy"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Gmart Smart. All rights reserved.</p>
    </footer>
</body>
</html>
