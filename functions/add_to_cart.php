<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: /furniture/login.php");
    exit;
}

// Get the furniture_id from the form submission
if (isset($_POST['furniture_id'])) {
    $user_id = $_SESSION['user_id']; // Get user ID from session
    $furniture_id = $_POST['furniture_id']; // Get the furniture ID from the form
    $quantity = 1; // Default quantity, can be modified later to support multiple quantities

    // Include database connection
    include 'C:\xampp\htdocs\furniture\includes\db.php';

    // Check if the item is already in the cart for this user
    $check_cart_query = "SELECT id, quantity FROM cart WHERE user_id = ? AND furniture_id = ?";
    $stmt = $conn->prepare($check_cart_query);
    $stmt->bind_param('ii', $user_id, $furniture_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If item is already in the cart, increment the quantity
        $cart_item = $result->fetch_assoc();
        $new_quantity = $cart_item['quantity'] + 1; // Increment quantity by 1

        // Update the cart with the new quantity
        $update_query = "UPDATE cart SET quantity = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param('ii', $new_quantity, $cart_item['id']);
        $update_stmt->execute();
    } else {
        // If item is not in the cart, insert a new row
        $insert_query = "INSERT INTO cart (user_id, furniture_id, quantity) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param('iii', $user_id, $furniture_id, $quantity);
        $insert_stmt->execute();
    }

    // Redirect to the cart page after adding the item
    header("Location: /furniture/furnitures.php");
    exit;
}
