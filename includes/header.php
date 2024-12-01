<?php
session_start();

$base_url = '/MyWebProject'
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stylehub</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">
</head>

<body>
<header>
    <div class="logo">
        <img src="<?php echo $base_url;?>/assets/images/logo.jpg" alt="StyleHub Logo">
        <h1> StyleHub </h1>
    </div>
    <nav>
        <ul>
            <li><a href="<?php echo $base_url;?>/index.php">Home</a></li>
            <li><a href="<?php echo $base_url;?>/pages/about.php">About Us</a></li>
            <li><a href="<?php echo $base_url;?>/pages/services.php">Services</a></li>
            <li><a href="<?php echo $base_url;?>/pages/gallery.php">Gallery</a></li>
            <li><a href="<?php echo $base_url;?>/pages/contact.php">Contact</a></li>

            <?php
            // Check if a user is logged in
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {

                // Check if seller
                if (isset($_SESSION['is_seller']) && $_SESSION['is_seller'] === true) {
                    echo '<li><a href="' . $base_url . '/pages/dashboard.php">Dashboard</a></li>';
                }

                // Check if admin
                if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
                    echo '<li><a href="' . $base_url . '/pages/admin.php">Admin</a></li>';
                }

                echo '<li><a href="' . $base_url . '/pages/logout.php">Logout</a></li>';
            } else {
               echo '<li><a href="' . $base_url . '/pages/login.php" class="login-btn">Login</a></li>';
            }
            ?>
        </ul>
    </nav>
</header>
</body>
</html>