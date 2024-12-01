<!DOCTYPE html>
<html lang="en">

<?php
require_once '../includes/db.php';
include '../includes/header.php';

// Start the session to access logged-in user information
session_start();

// Check if the user is logged in and is a seller
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['is_seller']) || $_SESSION['is_seller'] !== true) {
    header('Location: ../index.php');
    exit;
}

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get seller ID from the session (assuming it's stored in $_SESSION)
    $sellerID = $_SESSION['seller_id'];

    // Retrieve form data
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = '../assets/uploads/';
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Validate the file type
        $allowedTypes = ['jpg', 'jpeg', 'png'];
        if (!in_array($imageFileType, $allowedTypes)) {
            $errorMessage = "Only JPG, JPEG, and PNG files are allowed.";
        } elseif ($_FILES['image']['size'] > 5000000) { // 5MB limit
            $errorMessage = "The file is too large. Maximum allowed size is 5MB.";
        } else {
            // Move uploaded file to the target directory
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $errorMessage = "Failed to upload the image. Please try again.";
            }
        }
    } else {
        $uploadFile = ''; // No image uploaded
    }

    // Validate input
    if (empty($errorMessage) && !empty($description) && !empty($price) && !empty($stock) && is_numeric($price) && is_numeric($stock)) {
        try {
            // Insert the item into the Item table
            $sql = "INSERT INTO Item (sellerID, description, price, stock, imageURL) VALUES (:sellerID, :description, :price, :stock, :imageURL)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':sellerID', $sellerID, PDO::PARAM_INT);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
            $stmt->bindParam(':imageURL', $uploadFile, PDO::PARAM_STR); // Store the file path
            $stmt->execute();

            // Success message
            $successMessage = "Item added successfully!";
        } catch (PDOException $e) {
            $errorMessage = "An error occurred while adding your item. Please try again.";
        }
    } elseif (empty($errorMessage)) {
        $errorMessage = "Please fill in all fields with valid data.";
    }
}
?>

<body>
<main>
    <section class="dashboard-intro">
        <h2>Seller Dashboard</h2>
        <p>Welcome to your dashboard! Use the form below to add new items to the marketplace.</p>
    </section>

    <section class="dashboard-container">
        <div class="dashboard-form">
            <!-- Display messages -->
            <?php if (!empty($successMessage)): ?>
                <div class="success-message"><?= htmlspecialchars($successMessage) ?></div>
            <?php endif; ?>
            <?php if (!empty($errorMessage)): ?>
                <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
            <?php endif; ?>

            <!-- Form to add a new item -->
            <form id="itemForm" action="dashboard.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="description">Item Description:</label>
                    <textarea id="description" name="description" rows="3" placeholder="Enter a description of the item" required></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Price ($):</label>
                    <input type="text" id="price" name="price" placeholder="Enter the price" required>
                </div>

                <div class="form-group">
                    <label for="stock">Stock Quantity:</label>
                    <input type="text" id="stock" name="stock" placeholder="Enter the stock quantity" required>
                </div>

                <div class="form-group">
                    <label for="image">Upload Image:</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>

                <div class="form-group" style="margin-top: 30px;">
                    <button type="submit" class="btn">Add Item</button>
                </div>
            </form>
        </div>
    </section>
</main>

<?php
include '../includes/footer.php';
?>
</body>
</html>
