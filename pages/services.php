<!DOCTYPE html>
<html lang="en">

<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    include '../includes/header.php';

    // Handle pagination variables
    $itemsPerPage = 9; // Number of items per page
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
    $offset = ($page - 1) * $itemsPerPage; // Offset for SQL query

    // Fetch total number of items
    $totalItemsQuery = "SELECT COUNT(*) as total FROM Item WHERE stock > 0";
    $totalItemsResult = $conn->query($totalItemsQuery)->fetch(PDO::FETCH_ASSOC);
    $totalItems = $totalItemsResult['total'];
    $totalPages = ceil($totalItems / $itemsPerPage); // Calculate total pages

    // Fetch items for the current page
    $sql = "SELECT i.itemID, i.title, i.description, i.price, i.imageURL, s.sellerID, u.email 
        FROM Item i 
        JOIN Seller s ON i.sellerID = s.sellerID
        JOIN User u ON s.userID = u.userID
        WHERE i.stock > 0 
        LIMIT :limit OFFSET :offset";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':limit', $itemsPerPage, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle POST request for seller requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $buyerName = isset($_POST['buyer-name']) ? htmlspecialchars($_POST['buyer-name']) : '';
    $itemRequest = isset($_POST['item-request']) ? htmlspecialchars($_POST['item-request']) : '';
    $contactInfo = isset($_POST['contact-info']) ? htmlspecialchars($_POST['contact-info']) : '';

    if ($buyerName && $itemRequest && $contactInfo) {
        $insertQuery = "INSERT INTO Request (name, contact, request) VALUES (:name, :contact, :request)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bindParam(':name', $buyerName);
        $insertStmt->bindParam(':contact', $contactInfo);
        $insertStmt->bindParam(':request', $itemRequest);

        if ($insertStmt->execute()) {
            $responseMessage = "<p class='success-message'>Your request has been successfully submitted!</p>";
        } else {
            $responseMessage = "<p class='error-message'>There was an error submitting your request. Please try again.</p>";
        }
    } else {
        $responseMessage = "<p class='error-message'>All fields are required.</p>";
    }

    echo $responseMessage;
    exit; // End execution to prevent outputting other HTML
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
<main>
    <section class="services-intro">
        <h2>Our Services Marketplace</h2>
        <p>Explore our services provided by the community. Whether you're looking for items to buy or have something to sell, this is the place for you!</p>
    </section>

    <section class="marketplace-buyers">
        <h2>Market</h2>

        <!-- Dynamic Content Container -->
        <div id="marketplace-grid-container">
            <div class="marketplace-grid">
                <?php
                if (empty($items)) {
                    echo '<h3>No items found</h3>';
                }

                foreach ($items as $item) {
                    $itemName = htmlspecialchars($item['title']);
                    ?>
                    <article class="marketplace-item">
                        <img src="<?php echo htmlspecialchars($item['imageURL']); ?>" alt="No Image">
                        <h3><?php echo $itemName; ?></h3>
                        <p>Selling: <?php echo htmlspecialchars($item['description']); ?></p>
                        <p><strong>Price: $<?php echo number_format($item['price'], 2); ?></strong></p>
                        <a href="mailto:<?php echo htmlspecialchars($item['email']); ?>" class="btn">Contact Seller</a>
                    </article>
                    <?php
                }
                ?>
            </div>
        </div>

        <!-- Pagination Links -->
        <div class="pagination">
            <div style="margin-right: 3px">Page:</div>
            <a href="#" class="pagination-link" data-page="<?= $page - 1 ?>" <?= $page <= 1 ? 'style="display:none;"' : '' ?>>Previous</a>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="#" class="pagination-link <?= $i === $page ? 'active' : '' ?>" data-page="<?= $i ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            <a href="#" class="pagination-link" data-page="<?= $page + 1 ?>" <?= $page >= $totalPages ? 'style="display:none;"' : '' ?>>Next</a>
        </div>
    </section>

    <!-- Sellers Section -->
    <section class="marketplace-sellers">
        <h2>Looking for Something?</h2>
        <p>If you're searching for a specific service or product, request it here and sellers can contact you!</p>

        <form action="services.php" method="post" class="seller-request-form" id="sellerRequestForm">
            <label for="buyer-name">Your Name:</label>
            <input type="text" id="buyer-name" name="buyer-name" autocomplete="off" required>

            <label for="item-request">Service or Product You're Looking For:</label>
            <input type="text" id="item-request" name="item-request" autocomplete="off" required>

            <label for="contact-info">Contact Information:</label>
            <input type="text" id="contact-info" name="contact-info" autocomplete="off" required>

            <button type="submit">Submit Request</button>
        </form>

        <!-- Message placeholder -->
        <div id="responseMessage"></div>
    </section>
</main>

<?php
include '../includes/footer.php';
?>

<script>
    $(document).on('click', '.pagination-link', function(e) {
        e.preventDefault();

        const page = $(this).data('page');
        if (!page) return;

        // Make an AJAX request to fetch items for the selected page
        $.ajax({
            url: 'services.php',
            type: 'GET',
            data: { page: page },
            success: function(response) {
                // Replace the marketplace grid with new content
                $('#marketplace-grid-container').html($(response).find('#marketplace-grid-container').html());

                // Update the pagination links
                $('.pagination').html($(response).find('.pagination').html());

                // Scroll to the top of the marketplace section
                $('html, body').animate({
                    scrollTop: $('.marketplace-buyers').offset().top
                }, 800); // 800ms animation duration
            },
            error: function() {
                alert('Failed to load items. Please try again.');
            }
        });
    });

    // Handle AJAX form submission for seller requests
    $('#sellerRequestForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        // Get form data
        var formData = $(this).serialize();

        // Send AJAX request to submit the form
        $.ajax({
            url: 'services.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                // Update the message container with the success or error message
                $('#responseMessage').html(response).fadeIn();

                // Optionally, reset the form after submission
                $('#sellerRequestForm')[0].reset();
            },
            error: function() {
                $('#responseMessage').html('<p class="error-message">Failed to submit your request. Please try again.</p>').fadeIn();
            }
        });
    });
</script>

</body>
</html>
