<!DOCTYPE html>
<html lang="en">

<?php
require_once '../includes/db.php';
include '../includes/header.php';
$sql = "SELECT imageURL FROM Item WHERE stock > 0 LIMIT 10;";

$stmt = $conn->prepare($sql);
$stmt->execute();

$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
<section class="gallery-intro">
    <!-- Introduction section for the gallery -->
    <h2>Our Gallery</h2>
    <p>Explore our latest fashion collections, trends, and styles showcased in our gallery. Click on any image to
        view
        in full size.</p>
</section>

<section class="gallery-grid">
    <?php
    // First check if items is empty or not
    if (empty($items)) {
        echo '<h3>No items found</h3>';
    }

    // Loop through the items and display them
    foreach ($items as $item) {
        // Check if imageURL is empty or null
        if (empty($item['imageURL'])) {
            continue; // Skip this iteration if no image URL
        }
        ?>
        <div class="gallery-item">
            <a href="<?php echo htmlspecialchars($item['imageURL']); ?>" target="_blank">
                <!-- Link to full-sized image -->
                <img src="<?php echo htmlspecialchars($item['imageURL']); ?>" alt="Gallery Image">
                <!-- Image thumbnail with alt text -->
            </a>
        </div>
        <?php
    }
    ?>
</section>

<?php
include '../includes/footer.php';
?>

<!-- Linking external JavaScript file -->
<script src="../assets/js/script.js"></script>
</body>
</html>