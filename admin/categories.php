<?php
session_start(); // Ensure the session is started

// Check if user session variables are set and user role is ADMIN
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || $_SESSION['role'] !== 'ADMIN') {
    // Redirect to login page if not logged in or not an admin
    header("Location: /furniture/login.php");
    exit; // Stop further execution
}

// Include database connection file
include 'C:\xampp\htdocs\furniture\includes\db.php';

// Variable to hold error message
$error_message = "";
$message_type = "";

// Handle category addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $category_name = $_POST['name'];

    // Check if category already exists
    $check_query = "SELECT id FROM category WHERE name = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $category_name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Error message if category already exists
        $error_message = "Category already exists.";
        $message_type = "error";
    } else {
        // Prepare and execute the insert query
        $query = "INSERT INTO category (name) VALUES (?)";  // Removed description column from query
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $category_name);

        if ($stmt->execute()) {
            // Success message if query is successful
            $error_message = "Category added successfully!";
            $message_type = "success";
        } else {
            // Error message if query fails
            $error_message = "Failed to add category.";
            $message_type = "error";
        }
    }

    $stmt->close();
}

// Handle category deletion with POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category_id'])) {
    $category_id = $_POST['delete_category_id'];

    // Prepare and execute the delete query
    $delete_query = "DELETE FROM category WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        // Success message
        $error_message = "Category deleted successfully!";
        $message_type = "success";
    } else {
        // Error message
        $error_message = "Failed to delete category.";
        $message_type = "error";
    }

    $stmt->close();
}

// Query to fetch all categories
$query = "SELECT id, name, created_at FROM category ORDER BY created_at DESC"; // Removed description column from query
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'C:\xampp\htdocs\furniture\includes\head.php'; ?>
</head>

<body class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-72 bg-white shadow-sm">
        <?php include 'C:\xampp\htdocs\furniture\includes\sidebar.php'; ?>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <!-- Category Add Form -->
        <form action="categories.php" method="POST" class="bg-white rounded mb-6">
            <h2 class="text-xl font-semibold mb-4">Add New Category</h2>

            <!-- Display error message above the input field if there's any -->
            <?php if ($error_message): ?>
                <div class="bg-<?php echo $message_type === 'error' ? 'red' : 'green'; ?>-100 border border-<?php echo $message_type === 'error' ? 'red' : 'green'; ?>-400 text-<?php echo $message_type === 'error' ? 'red' : 'green'; ?>-700 px-4 py-3 rounded relative mb-4">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
                <input type="text" name="name" id="name" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <button type="submit" name="add_category" class="block rounded bg-gray-900 px-4 py-3 text-sm font-medium text-white transition hover:scale-105">Add Category</button>
        </form>

        <!-- Display Existing Categories -->
        <?php if ($result->num_rows > 0): ?>
            <table class="min-w-full bg-white border border-gray-300 shadow rounded">
                <thead>
                    <tr>
                        <th class="border px-4 py-2 text-left">Category Name</th>
                        <th class="border px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="border px-4 py-2">
                                <!-- Delete Button (inside a form) -->
                                <form action="categories.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                    <input type="hidden" name="delete_category_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-700">No categories found.</p>
        <?php endif; ?>

        <?php $result->free(); ?>
    </main>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>