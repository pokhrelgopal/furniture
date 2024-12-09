<?php
// Include the db connection file
include 'C:\xampp\htdocs\furniture\includes\db.php';

// Start the session to get the logged-in user's ID
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: /furniture/login.php");
    exit;
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Query to get orders placed by the logged-in user
$query = "
    SELECT orders.id AS order_id, orders.status, orders.created_at, order_items.furniture_id, order_items.quantity, order_items.price, furniture.name AS furniture_name
    FROM orders
    LEFT JOIN order_items ON orders.id = order_items.order_id
    LEFT JOIN furniture ON order_items.furniture_id = furniture.id
    WHERE orders.user_id = ? ORDER BY orders.created_at DESC
";

// Prepare and execute the query
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'C:\xampp\htdocs\furniture\includes\head.php'; ?>
</head>

<body>
    <?php include './includes/navbar.php'; ?>

    <main class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4">My Bookings</h1>

        <?php if ($result->num_rows > 0): ?>
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="border px-4 py-2 text-left">Order ID</th>
                        <th class="border px-4 py-2 text-left">Furniture Name</th>
                        <th class="border px-4 py-2 text-left">Quantity</th>
                        <th class="border px-4 py-2 text-left">Price</th>
                        <th class="border px-4 py-2 text-left">Status</th>
                        <th class="border px-4 py-2 text-left">Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch and display orders and items
                    while ($row = $result->fetch_assoc()):
                    ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($row['furniture_name']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td class="border px-4 py-2"><?php echo "$" . number_format($row['price'], 2); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($row['status']); ?></td>
                            <td class="border px-4 py-2"><?php echo date("Y-m-d H:i:s", strtotime($row['created_at'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-700">You have no bookings yet.</p>
        <?php endif; ?>

        <?php $stmt->close(); ?>
    </main>

</body>

</html>