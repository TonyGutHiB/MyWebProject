<?php
require_once '../includes/db.php';

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
    $sellerID = $_SESSION['seller_id'];

    // Retrieve form data
    $title = trim($_POST['title']);
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
    if (empty($errorMessage) && !empty($title) && !empty($description) && !empty($price) && !empty($stock) && is_numeric($price) && is_numeric($stock)) {
        try {
            // Insert the item into the Item table
            $sql = "INSERT INTO Item (sellerID, title, description, price, stock, imageURL) VALUES (:sellerID, :title, :description, :price, :stock, :imageURL)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':sellerID', $sellerID, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
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

    // Return response in JSON format for AJAX
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo json_encode(['success' => !empty($successMessage), 'message' => $successMessage ?: $errorMessage]);
        exit;
    }
} else {
    include '../includes/header.php';
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
            <!-- Display messages dynamically -->
            <div id="responseMessage"></div>

            <!-- Form to add a new item -->
            <form id="itemForm" action="dashboard.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Item Title:</label>
                    <input type="text" id="title" name="title" placeholder="Enter the item title" required>
                </div>

                <div class="form-group">
                    <label for="description">Item Description:</label>
                    <textarea id="description" name="description" rows="3" placeholder="Enter a description of the item"
                              required></textarea>
                </div>

                <div class="form-group-inline">
                    <div class="form-group">
                        <label for="price">Price ($):</label>
                        <input type="number" id="price" name="price" placeholder="Enter the price" required>
                    </div>

                    <div class="form-group">
                        <label for="stock">Stock Quantity:</label>
                        <input type="number" id="stock" name="stock" placeholder="Enter the stock quantity" required>
                    </div>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Handle form submission via AJAX
        $('#itemForm').on('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            const formData = new FormData(this); // Serialize the form data

            // Send the AJAX request
            $.ajax({
                url: 'dashboard.php', // Send request to the same page
                type: 'POST',
                data: formData, // Send the form data
                contentType: false, // Don't set content type
                processData: false, // Don't process the data
                success: function (response) {
                    const data = JSON.parse(response);

                    // Display success or error message
                    if (data.success) {
                        $('#responseMessage').html('<div class="success-message">' + data.message + '</div>');
                    } else {
                        $('#responseMessage').html('<div class="error-message">' + data.message + '</div>');
                    }
                },
                error: function () {
                    alert('An error occurred. Please try again.');
                }
            });
        });
    });
</script>