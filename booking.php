<?php
session_start();
include 'C:\xampp\htdocs\furniture\includes\db.php';
include 'C:\xampp\htdocs\furniture\includes\head.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: /furniture/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// Handle booking confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_booking'])) {
    $receiver_name = $_POST['receiver_name'];
    $phone_number = $_POST['phone_number'];
    $address_line = $_POST['address_line'];
    $city = $_POST['city'];
    $state = $_POST['state'];

    // Validate address fields
    if (empty($receiver_name) || empty($phone_number) || empty($address_line) || empty($city) || empty($state)) {
        $message = "Please fill in all fields.";
        $message_type = "danger";
    } else {
        $conn->begin_transaction();
        // Save address
        try {
            $query = "INSERT INTO address (user_id, receiver_name, phone_number, address_line, city, state) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("isssss", $user_id, $receiver_name, $phone_number, $address_line, $city, $state);
            $stmt->execute();
            $stmt->close();

            $address = "$address_line, $city, $state";
            // Create order
            $query = "INSERT INTO orders (user_id, total_price, status, address) VALUES (?, 0, 'Pending', ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("is", $user_id, $address);
            $stmt->execute();
            $order_id = $stmt->insert_id;
            $stmt->close();

            // Process cart items
            $total_price = 0;
            $query = "SELECT c.furniture_id, c.quantity, f.price 
              FROM cart c 
              JOIN furniture f ON c.furniture_id = f.id 
              WHERE c.user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $cart_items = $stmt->get_result();

            while ($item = $cart_items->fetch_assoc()) {
                $furniture_id = $item['furniture_id'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                $subtotal = $price * $quantity;
                $total_price += $subtotal;

                $insert_item_query = "INSERT INTO order_items (order_id, furniture_id, quantity, price) VALUES (?, ?, ?, ?)";
                $item_stmt = $conn->prepare($insert_item_query);
                $item_stmt->bind_param("iiid", $order_id, $furniture_id, $quantity, $price);
                $item_stmt->execute();
                $item_stmt->close();
            }
            $stmt->close();

            // Update order total
            $query = "UPDATE orders SET total_price = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("di", $total_price, $order_id);
            $stmt->execute();
            $stmt->close();

            // Clear the cart
            $query = "DELETE FROM cart WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();

            $conn->commit();
            $message = "Booking successful! We will contact you shortly.";
            $message_type = "success";
        } catch (\Throwable $th) {
            $conn->rollback();
            $message = "Booking failed: " . $e->getMessage();
            $message_type = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Booking Page</title>
</head>

<body>
    <?php include './includes/navbar.php'; ?>
    <main class="container mx-auto mt-6">
        <!-- <h1 class="text-2xl font-bold mb-4">Confirm Your Booking</h1> -->
        <?php if ($message): ?>
            <div class="bg-<?php echo $message_type === 'success' ? 'green' : 'red'; ?>-100 border border-<?php echo $message_type === 'success' ? 'green' : 'red'; ?>-400 text-<?php echo $message_type === 'success' ? 'green' : 'red'; ?>-700 px-4 py-3 rounded relative mb-4">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <div class="w-full flex gap-5">
            <!-- Address Form -->
            <div class="w-1/2 bg-white shadow rounded p-6">
                <h2 class="text-lg font-semibold mb-4">Enter Address</h2>
                <form method="POST">
                    <div class="mb-4">
                        <label for="receiver_name" class="block text-sm font-medium">Receiver Name</label>
                        <input type="text" id="receiver_name" name="receiver_name" class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="phone_number" class="block text-sm font-medium">Phone Number</label>
                        <input type="text" id="phone_number" name="phone_number" class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="address_line" class="block text-sm font-medium">Address Line</label>
                        <input type="text" id="address_line" name="address_line" class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="city" class="block text-sm font-medium">City</label>
                        <input type="text" id="city" name="city" class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="state" class="block text-sm font-medium">State</label>
                        <input type="text" id="state" name="state" class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 text-sm" required>
                    </div>
                    <button type="submit" name="confirm_booking" class="block rounded bg-gray-900 px-4 py-3 text-sm font-medium text-white transition hover:scale-105">
                        Confirm Booking
                    </button>
                </form>
            </div>

            <!-- Cart Items -->
            <div class="w-1/2 h-fit bg-white shadow rounded p-6 ml-4">
                <h2 class="text-lg font-semibold mb-4">Cart Items</h2>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="border-b py-2">Item</th>
                            <th class="border-b py-2">Quantity</th>
                            <th class="border-b py-2">Price</th>
                            <th class="border-b py-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT c.quantity, f.name, f.price 
                                  FROM cart c 
                                  JOIN furniture f ON c.furniture_id = f.id 
                                  WHERE c.user_id = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $cart_items = $stmt->get_result();
                        $stmt->close();

                        $total = 0;

                        while ($row = $cart_items->fetch_assoc()) {
                            $subtotal = $row['quantity'] * $row['price'];
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td class="py-2"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td class="py-2"><?php echo htmlspecialchars($row['quantity']); ?></td>
                                <td class="py-2">Rs. <?php echo number_format($row['price'], 2); ?></td>
                                <td class="py-2">Rs. <?php echo number_format($subtotal, 2); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="mt-4">
                    <p class="font-bold flex items-center justify-between"><span>Total</span> <span>Rs. <?php echo number_format($total, 2); ?>/month</span></p>
                </div>
            </div>
        </div>
    </main>
</body>

</html>