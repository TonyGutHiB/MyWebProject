<?php
require_once '../includes/db.php';
include '../includes/header.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Promote a user to seller
    if (isset($_POST['promote_user_id'])) {
        $userID = intval($_POST['promote_user_id']);

        try {
            // Check if the user is already a seller
            $checkQuery = "SELECT COUNT(*) FROM Seller WHERE userID = :userID";
            $stmt = $conn->prepare($checkQuery);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->execute();
            $isSeller = $stmt->fetchColumn();

            if ($isSeller) {
                $errorMessage = "This user is already a seller.";
            } else {
                // Promote the user to seller
                $insertQuery = "INSERT INTO Seller (userID) VALUES (:userID)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
                $stmt->execute();

                $successMessage = "User promoted to seller successfully!";
            }
        } catch (PDOException $e) {
            $errorMessage = "An error occurred while promoting the user. Please try again.";
        }
    }
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
<body>
<main>
    <section class="admin-intro">
        <h2>Admin Dashboard</h2>
        <p>Welcome to the admin dashboard. Use the tools below to manage users.</p>
    </section>

    <section class="admin-container">
        <!-- Display messages -->
        <?php if (!empty($successMessage)): ?>
            <div class="success-message"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <!-- Search Users -->
        <form id="searchForm" action="admin.php" method="get" class="admin-search">
            <div class="form-group">
                <label for="search_email">Search Users by Email:</label>
                <input type="text" id="search_email" name="search_email" placeholder="Enter email to search">
                <button type="submit" class="btn">Search</button>
            </div>
            <!-- Display Search Results -->
            <?php if (!empty($users)): ?>
                <h3>Search Results:</h3>
                <table class="search-result-table">
                    <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['userID']) ?></td>
                            <td>
                                <div class="email-scroll">
                                <?= htmlspecialchars($user['email']) ?>
                                </div>
                            </td>
                        <td>
                            <!-- Promote to seller button -->
                            <form action="admin.php" method="post" style="display:inline;">
                                <input type="hidden" name="promote_user_id"
                                       value="<?= htmlspecialchars($user['userID']) ?>">
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
        </form>
    </section>
</main>

<?php
include '../includes/footer.php';
?>
</body>
</html>
