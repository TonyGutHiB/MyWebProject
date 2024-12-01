<!DOCTYPE html>
<html lang="en">

<?php
require_once '../includes/db.php';
include '../includes/header.php';

$sql = "SELECT i.itemID, i.description, i.price, i.imageURL, s.sellerID, u.email 
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
      <!-- Introduction to the services offered -->
      <h2>Our Services Marketplace</h2>
      <p>Explore our services provided by the community. Whether you're looking for items to buy or have something to sell, this is the place for you!</p>
    </section>

      <section class="marketplace-buyers">
          <h2>Market</h2>
          <div class="marketplace-grid">
              <?php
              // First check if items is empty or not
                if (empty($items)) {
                    echo '<h3>No items found</h3>';
                }

              // Loop through the items and display them
              foreach ($items as $item) {
                  // Get seller name from the email (you can adjust this if the seller name is different)
                  $sellerName = explode('@', $item['email'])[0];  // Extracts the part before '@' for demonstration
                  ?>
                  <article class="marketplace-item">
                      <img src="<?php echo htmlspecialchars($item['imageURL']); ?>" alt="<?php echo htmlspecialchars($item['description']); ?>">
                      <h3><?php echo htmlspecialchars($sellerName); ?></h3>
                      <p>Selling: <?php echo htmlspecialchars($item['description']); ?></p>
                      <p><strong>Price: $<?php echo number_format($item['price'], 2); ?></strong></p>
                      <button>Contact Seller</button>
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
      <!-- Form for buyers to submit requests for specific services or products -->
      <form action="#" method="post" class="seller-request-form">
        <label for="buyer-name">Your Name:</label>
        <input type="text" id="buyer-name" name="buyer-name" required>

        <label for="item-request">Service or Product You're Looking For:</label>
        <input type="text" id="item-request" name="item-request" required>

        <label for="contact-info">Contact Information:</label>
        <input type="email" id="contact-info" name="contact-info" required>

        <button type="submit">Submit Request</button>
      </form>
    </section>
  </main>

  <?php
    include '../includes/footer.php';
  ?>

  <!-- Linking external JavaScript file -->
  <script src="../assets/js/script.js"></script>
</body>
</html>