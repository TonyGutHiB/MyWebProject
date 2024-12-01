<!DOCTYPE html>
<html lang="en">

<?php
require_once '../includes/db.php';
include '../includes/header.php';

// Handle POST request to insert a new request into the Request table
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $buyerName = trim($_POST['buyer-name']);
    $itemRequest = trim($_POST['item-request']);
    $contactInfo = trim($_POST['contact-info']);

    // Validate input
    if (!empty($buyerName) && !empty($itemRequest) && !empty($contactInfo)) {
        try {
            // Insert the request into the Request table
            $sql = "INSERT INTO Request (name, request, contact) VALUES (:buyerName, :itemRequest, :contactInfo)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':buyerName', $buyerName, PDO::PARAM_STR);
            $stmt->bindParam(':itemRequest', $itemRequest, PDO::PARAM_STR);
            $stmt->bindParam(':contactInfo', $contactInfo, PDO::PARAM_STR);
            $stmt->execute();

            // Success message
            $successMessage = "Your request has been submitted successfully!";
        } catch (PDOException $e) {
            $errorMessage = "An error occurred while submitting your request. Please try again.";
        }
    } else {
        $errorMessage = "Please fill in all fields with valid data.";
    }
}

// Fetch items to display as before
$sql = "SELECT i.itemID, i.title, i.description, i.price, i.imageURL, s.sellerID, u.email 
        FROM Item i 
        JOIN Seller s ON i.sellerID = s.sellerID
        JOIN User u ON s.userID = u.userID
        WHERE i.stock > 0 
        LIMIT 10;"; // We only want to display 10 items

$stmt = $conn->prepare($sql);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
<main>
    <section class="services-intro">
        <h2>Our Services Marketplace</h2>
        <p>Explore our services provided by the community. Whether you're looking for items to buy or have something to sell, this is the place for you!</p>
    </section>

    <section class="marketplace-buyers">
        <h2>Market</h2>
        <div class="marketplace-grid">
            <?php
            if (empty($items)) {
                echo '<h3>No items found</h3>';
            }

            foreach ($items as $item) {
                $itemName = htmlspecialchars($item['title']);
                ?>
                <article class="marketplace-item">
                    <img src="<?php echo htmlspecialchars($item['imageURL']); ?>" alt="<?php echo htmlspecialchars($item['description']); ?>">
                    <h3><?php echo $itemName; ?></h3>
                    <p>Selling: <?php echo htmlspecialchars($item['description']); ?></p>
                    <p><strong>Price: $<?php echo number_format($item['price'], 2); ?></strong></p>
                    <!-- Mailto link for contacting seller -->
                    <a href="mailto:<?php echo htmlspecialchars($item['email']); ?>" class="btn">Contact Seller</a>
                </article>
                <?php
            }
            ?>
        </div>
    </section>

    <!-- Sellers Section -->
    <section class="marketplace-sellers">
        <h2>Looking for Something?</h2>
        <p>If you're searching for a specific service or product, request it here and sellers can contact you!</p>

        <?php
        if (!empty($successMessage)) {
            echo '<div class="success-message">' . htmlspecialchars($successMessage) . '</div>';
        }
        if (!empty($errorMessage)) {
            echo '<div class="error-message">' . htmlspecialchars($errorMessage) . '</div>';
        }
        ?>

        <form action="services.php" method="post" class="seller-request-form">
            <label for="buyer-name">Your Name:</label>
            <input type="text" id="buyer-name" name="buyer-name" required>

            <label for="item-request">Service or Product You're Looking For:</label>
            <input type="text" id="item-request" name="item-request" required>

            <label for="contact-info">Contact Information:</label>
            <input type="text" id="contact-info" name="contact-info" required>

            <button type="submit">Submit Request</button>
        </form>
    </section>
</main>

<?php
include '../includes/footer.php';
?>

<script src="../assets/js/script.js"></script>
</body>
</html>
