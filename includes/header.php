<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stylehub</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<header>
    <div class="logo">
        <img src="images/logo.jpg" alt="StyleHub Logo">
        <h1> StyleHub </h1>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="pages/about.html">About Us</a></li>
            <li><a href="pages/services.html">Services</a></li>
            <li><a href="pages/gallery.html">Gallery</a></li>
            <li><a href="pages/contact.html">Contact</a></li>

            <?php
            // Check if a user is logged in
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                echo '<li><a href="pages/dashboard.php">Dashboard</a></li>';
                echo '<li><a href="pages/logout.php">Logout</a></li>';
            } else {
               echo '<li><a href="pages/login.php" class="login-btn">Login</a></li>';
            }
            ?>
        </ul>
    </nav>
</header>
</body>
</html>