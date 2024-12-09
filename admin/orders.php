<?php
session_start(); // Ensure the session is started
// Check if user session variables are set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || $_SESSION['role'] !== 'ADMIN') {
    // Redirect to login page if not logged in
    header("Location: /furniture/login.php");
    exit; // Stop further execution
}
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
        Orders
    </main>
</body>

</html>