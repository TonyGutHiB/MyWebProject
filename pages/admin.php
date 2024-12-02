<?php
require_once '../includes/db.php';

// Start session to access logged-in user information
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../index.php');
    exit;
}

$successMessage = '';
$errorMessage = '';
$users = [];

// Handle the promote user functionality via AJAX POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['promote_user_id'])) {
    $userID = intval($_POST['promote_user_id']);
    $response = ['success' => false, 'message' => ''];

    try {
        // Check if the user is already a seller
        $checkQuery = "SELECT COUNT(*) FROM Seller WHERE userID = :userID";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt->execute();
        $isSeller = $stmt->fetchColumn();

        if ($isSeller) {
            $response['message'] = "User with ID $userID is already a seller.";
        } else {
            // Promote the user to seller
            $insertQuery = "INSERT INTO Seller (userID) VALUES (:userID)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->execute();

            $response['success'] = true;
            $response['message'] = "User promoted to seller successfully!";
        }
    } catch (PDOException $e) {
        $response['message'] = "An error occurred while promoting the user. Please try again.";
    }

    // If the request is AJAX, return a JSON response
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode($response);
        exit;
    }
} else {
    include '../includes/header.php';
}

// Search users by email
if (isset($_GET['search_email'])) {
    $email = trim($_GET['search_email']);
    try {
        $searchQuery = "SELECT userID, email FROM User WHERE email LIKE :email";
        $stmt = $conn->prepare($searchQuery);
        $searchTerm = "%" . $email . "%";
        $stmt->bindParam(':email', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $errorMessage = "An error occurred while searching for users. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<main>
    <section class="admin-intro">
        <h2>Admin Dashboard</h2>
        <p>Welcome to the admin dashboard. Use the tools below to manage users.</p>
    </section>

    <section class="admin-container">

        <div id="responseMessage"></div>
        <!-- Search Users -->
        <section class="admin-search">
            <form id="searchForm" action="admin.php" method="get">
                <div class="form-group">
                    <label for="search_email">Search Users by Email:</label>
                    <input type="text" id="search_email" name="search_email" placeholder="Enter email to search">
                    <button type="submit" class="btn">Search</button>
                </div>
            </form>

            <div id="search-results">
                <?php if (!empty($users)): ?>
                    <h3>Search Results:</h3>
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $user):

                            if ($user['userID'] == 1) { // admin user skipped
                                continue;
                            }

                            ?>
                            <tr>
                                <td><?= htmlspecialchars($user['userID']) ?></td>
                                <td>
                                    <div class="email-scroll">
                                        <?= htmlspecialchars($user['email']) ?>
                                    </div>
                                </td>
                                <td>
                                    <form action="admin.php" method="post" style="display:inline;" class="promote-user">
                                        <input type="hidden" name="promote_user_id" value="<?= htmlspecialchars($user['userID']) ?>">
                                        <button type="submit" class="text-link">Promote to Seller</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php elseif (isset($_GET['search_email'])): ?>
                    <p class="no-users">No users found matching your search.</p>
                <?php endif; ?>
            </div>
        </section>
    </section>
</main>

<?php include '../includes/footer.php'; ?>

<script>
    $(document).ready(function () {
        // Handle the search form submission via AJAX
        $('#searchForm').on('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            const searchEmail = $('#search_email').val(); // Get email input value

            $.ajax({
                url: 'admin.php', // Send request to the same script
                type: 'GET',
                data: { search_email: searchEmail }, // Send the email as a parameter
                success: function (response) {
                    // Extract the new content and update the search-results section
                    const newContent = $(response).find('#search-results').html();
                    $('#search-results').html(newContent);
                },
                error: function () {
                    alert('An error occurred while searching. Please try again.');
                }
            });
        });

        // Handle the promote button click and submit via AJAX
        $(document).on('submit', 'form.promote-user', function (e) {
            e.preventDefault(); // Prevent default form submission

            const userID = $(this).find('input[name="promote_user_id"]').val(); // Get the userID
            const form = $(this); // Reference to the current form


            $.ajax({
                url: 'admin.php', // Send request to the same script
                type: 'POST',
                data: { promote_user_id: userID }, // Send the userID to be promoted
                success: function (response) {
                    const data = JSON.parse(response);

                    // Display success or error message
                    if (data.success) {
                        // Remove error message
                        $('#responseMessage').fadeOut().empty();
                        form.closest('tr').find('td:last').html('<span class="success-message">Promoted to Seller</span>'); // Change button text to show success
                    } else {
                        $('#responseMessage').html('<div class="error-message">' + data.message + '</div>').fadeIn();
                    }
                },
                error: function () {
                    alert('An error occurred while promoting the user. Please try again.');
                }
            });
        });
    });
</script>
</body>
</html>
