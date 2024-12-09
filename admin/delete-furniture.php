<?php
session_start();
include 'C:\xampp\htdocs\furniture\includes\db.php';

// Check if user is logged in and has admin privileges
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: /furniture/login.php");
    exit;
}

// Check if 'id' is present in the query string
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $furniture_id = intval($_GET['id']);

    // Prepare the DELETE query
    $query = "DELETE FROM furniture WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $furniture_id);
        $success = mysqli_stmt_execute($stmt);

        if ($success) {
            // Record successfully deleted
            $_SESSION['message'] = "Furniture item deleted successfully.";
            $_SESSION['message_type'] = "success";
        } else {
            // Error during deletion
            $_SESSION['message'] = "Error deleting furniture item.";
            $_SESSION['message_type'] = "error";
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['message'] = "Failed to prepare delete query.";
        $_SESSION['message_type'] = "error";
    }
} else {
    // Invalid ID
    $_SESSION['message'] = "Invalid furniture ID.";
    $_SESSION['message_type'] = "error";
}

// Redirect back to the furniture list page
header("Location: /furniture/admin/furnitures.php");
exit;
