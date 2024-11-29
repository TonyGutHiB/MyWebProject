<!DOCTYPE html>
<html lang="en">

<?php
require_once '../includes/db.php';
include '../includes/header.php';
?>

<body>
  <section class="gallery-intro">
    <!-- Introduction section for the gallery -->
    <h2>Our Gallery</h2>
    <p>Explore our latest fashion collections, trends, and styles showcased in our gallery. Click on any image to view in full size.</p>
  </section>

  <section class="gallery-grid">
    <!-- Grid layout for displaying gallery items -->
    <div class="gallery-item">
      <a href="../assets/images/dress.jpg" target="_blank"> <!-- Link to full-sized image -->
        <img src="../assets/images/dress.jpg" alt="Dress"> <!-- Image thumbnail with alt text -->
      </a>
    </div>
    <div class="gallery-item">
      <a href="../assets/images/jacket.jpg" target="_blank">
        <img src="../assets/images/jacket.jpg" alt="Jacket">
      </a>
    </div>
    <div class="gallery-item">
      <a href="../assets/images/watch.jpg" target="_blank">
        <img src="../assets/images/watch.jpg" alt="Watch">
      </a>
    </div>
    <div class="gallery-item">
      <a href="../assets/images/picone.jpg" target="_blank">
        <img src="../assets/images/picone.jpg" alt="Pic One">
      </a>
    </div>
    <div class="gallery-item">
      <a href="../assets/images/pictwo.jpg" target="_blank">
        <img src="../assets/images/pictwo.jpg" alt="Pic Two">
      </a>
    </div>
    <div class="gallery-item">
      <a href="../assets/images/picthree.jpg" target="_blank">
        <img src="../assets/images/picthree.jpg" alt="Pic Three">
      </a>
    </div>
    <div class="gallery-item">
      <a href="../assets/images/picfour.jpg" target="_blank">
        <img src="../assets/images/picfour.jpg" alt="Pic Four">
      </a>
    </div>
    <div class="gallery-item">
      <a href="../assets/images/clothes1.jpg" target="_blank">
        <img src="../assets/images/clothes1.jpg" alt="clothes 1">
      </a>
    </div>
  </section>

  <footer>
    <!-- Footer section with copyright information -->
    <p>&copy; 2024 StyleHub. All Rights Reserved.</p>
  </footer>

  <!-- Linking external JavaScript file -->
  <script src="../assets/js/script.js"></script>
</body>
</html>