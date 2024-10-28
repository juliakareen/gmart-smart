<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Gmart Smart</title>
    <style>
         body {
            background-image: url('assets/images/Website/stall.jpg');
        }
        
        /* Header styles */
        header {
            background-color: rgba(51, 51, 51, 0.8);
            color: white;
            padding: 10px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Logo styling for mobile */
        header img.logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }

        header h1 {
            font-size: 1.5em;
            margin: 0;
        }

        /* Navigation section styling */
        nav {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px;
        }

        .nav-section {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100%;
            max-width: 200px;
            margin: 5px;
        }

        .nav-section a {
            display: block;
            margin: 5px 0;
            padding: 5px;
            background-color: #FFD700;
            color: #333;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .nav-section a:hover {
            background-color: #333;
            color: white;
        }

        /* Main content styling */
        main {
            padding: 10px;
            text-align: center;
        }

        .info-sections {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .info-section {
            width: 100%;
            max-width: 250px;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: white;
        }

        .info-section h2 {
            font-size: 1.5em;
            color: #333;
        }

        .info-section p {
            font-size: 1em;
            color: blue;
            line-height: 1.6;
        }

        /* Map styling */
        .map {
            width: 100%;
            max-width: 100%;
            height: 200px;
            border: none;
            margin-top: 10px;
        }

        /* Footer styling */
        footer {
            background-color: rgba(51, 51, 51, 0.8);
            color: white;
            text-align: center;
            padding: 10px;
            width: 100%;
        }

        /* Larger screen adjustments */
        @media (min-width: 768px) {
            header {
                flex-direction: row;
            }
            header img.logo {
                width: 100px;
                margin-right: 15px;
                margin-bottom: 0;
            }
            header h1 {
                font-size: 2em;
            }
            nav {
                flex-direction: row;
                justify-content: center;
                gap: 20px;
            }
            .info-sections {
                flex-direction: row;
                gap: 20px;
                justify-content: center;
            }
            .info-section {
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <header>
        <img src="assets/images/website/logo.jpg" class="logo" alt="Gmart Smart Logo">
        <h1>Welcome to Gmart Smart</h1>
    </header>

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

    <main>
        <div class="info-sections">
            <section class="info-section">
                <h2>About Us</h2>
                <p>Gmart Smart is your one-stop shop for all your grocery needs.</p>
            </section>

            <section class="info-section">
                <h2>Contact Us</h2>
                <p>
                    <i class="fab fa-whatsapp"></i>
                    <strong>Phone:</strong> <a href="https://wa.me/60162681540" style="color: blue;">0162681540</a>
                </p>
            </section>
        </div>

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

    <footer>
        <p>&copy; 2024 Gmart Smart. All rights reserved.</p>
    </footer>
</body>
</html>
