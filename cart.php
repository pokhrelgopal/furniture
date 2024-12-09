<?php
session_start();
include 'C:\xampp\htdocs\furniture\includes\db.php';

// Check if user session variables are set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: /furniture/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle quantity updates (increment/decrement)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['cart_id'])) {
        $cart_id = $_POST['cart_id'];
        $action = $_POST['action'];

        // Fetch current quantity
        $query = "SELECT quantity FROM cart WHERE id = $cart_id AND user_id = $user_id";
        $result = mysqli_query($conn, $query);
        $cart_item = mysqli_fetch_assoc($result);
        $current_quantity = $cart_item['quantity'];

        // Update quantity based on the action
        if ($action === 'increment') {
            $new_quantity = $current_quantity + 1;
        } elseif ($action === 'decrement' && $current_quantity > 1) {
            $new_quantity = $current_quantity - 1;
        } else {
            // If trying to decrement below 1, delete the item
            $delete_query = "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id";
            mysqli_query($conn, $delete_query);
            header("Location: /furniture/cart.php"); // Refresh cart page after removal
            exit;
        }

        // Update the quantity in the database
        if ($action !== 'decrement' || $new_quantity >= 1) {
            $update_query = "UPDATE cart SET quantity = $new_quantity WHERE id = $cart_id AND user_id = $user_id";
            mysqli_query($conn, $update_query);
        }
    }
}

// Query to get cart items for the logged-in user
$query = "SELECT c.id, f.name, f.price, c.quantity, f.image_url
FROM cart c
JOIN furniture f ON c.furniture_id = f.id
WHERE c.user_id = $user_id";
$result = mysqli_query($conn, $query);

// Calculate total cost
$total_cost = 0;
$cart_items = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cart_items[] = $row;
        $total_cost += $row['price'] * $row['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include __DIR__ . '/includes/head.php'; ?>
</head>

<body>
    <?php include './includes/navbar.php'; ?>

    <main class="container mx-auto py-8 px-4">
        <div class="flex gap-10">
            <!-- Cart Items -->
            <div class="bg-white shadow p-4 w-full">
                <?php if (empty($cart_items)): ?>
                    <div class="flex min-h-[400px] flex-col items-center justify-center space-y-4 px-4 text-center">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-semibold tracking-tight">Your cart is empty</h2>
                        <p class="text-muted-foreground">
                            Add products while you shop, so they&apos;ll be ready for booking later.
                        </p>
                        <button class="mt-4 block rounded bg-gray-900 px-4 py-3 text-sm font-medium text-white transition hover:scale-105">
                            <a href="/furniture/furnitures.php">
                                Start shopping
                                </Link>
                        </Button>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="flex items-center justify-between">
                                <img src="/furniture/uploads/furniture/<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>" class="w-32 h-20 object-cover">
                                <div class="flex-1 pl-4">
                                    <h3 class="text-lg font-semibold"><?php echo $item['name']; ?></h3>
                                    <p class="text-gray-600">Rs. <?php echo number_format($item['price'], 2); ?></p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <!-- Increment and Decrement Buttons -->
                                    <form action="" method="POST" class="flex items-center space-x-2">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" name="action" value="decrement" class="text-gray-500 text-sm bg-gray-200 p-2 rounded-md hover:bg-gray-300"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                                            </svg>
                                        </button>
                                        <span class="text-gray-700"><?php echo $item['quantity']; ?></span>
                                        <button type="submit" name="action" value="increment" class="text-gray-500 text-sm bg-gray-200 p-2 rounded-md hover:bg-gray-300"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Cart Total -->
            <?php if (!empty($cart_items)): ?>
                <div class="bg-white shadow p-4 min-w-96 h-fit">
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-semibold">Total:</span>
                        <span class="text-xl font-semibold text-green-600">Rs. <?php echo number_format($total_cost, 2); ?></span>
                    </div>
                    <div class="mt-4">
                        <a href="/furniture/booking.php">
                            <button
                                type="button"
                                class="block w-full rounded bg-gray-900 px-4 py-3 text-sm font-medium text-white transition hover:scale-105">
                                Proceed to Booking
                            </button>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

</body>

</html>