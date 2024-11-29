<!DOCTYPE html>
<html lang="en">

<?php
require_once '../includes/db.php';
include '../includes/header.php';
?>

<body>
  <main>
    <section class="services-intro">
      <!-- Introduction to the services offered -->
      <h2>Our Services Marketplace</h2>
      <p>Explore our services provided by the community. Whether you're looking for items to buy or have something to sell, this is the place for you!</p>
    </section>

    <!-- Buyers Section -->
    <section class="marketplace-buyers">
      <h2>Market</h2>
      <div class="marketplace-grid">
        <!-- Individual marketplace items -->
        <article class="marketplace-item">
          <img src="../assets/images/nikeone.jpg" alt="Pair of Nike Running Shoes">
          <h3>Tony</h3>
          <p>Selling: Pair of Nike Running Shoes</p>
          <p><strong>Price: $50</strong></p>
          <button>Contact Seller</button>
        </article>
        <article class="marketplace-item">
          <img src="../assets/images/jacket.jpg" alt="Black Leather Jacket">
          <h3>Sara</h3>
          <p>Selling: Black Leather Jacket</p>
          <p><strong>Price: $80</strong></p>
          <button>Contact Seller</button>
        </article>
        <article class="marketplace-item">
          <img src="../assets/images/dress.jpg" alt="Floral Summer Dress">
          <h3>Alice</h3>
          <p>Selling: Floral Summer Dress</p>
          <p><strong>Price: $40</strong></p>
          <button>Contact Seller</button>
        </article>
        <article class="marketplace-item">
          <img src="../assets/images/watch.jpg" alt="Classic Wrist Watch">
          <h3>Michael</h3>
          <p>Selling: Classic Wrist Watch</p>
          <p><strong>Price: $120</strong></p>
          <button>Contact Seller</button>
        </article>
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