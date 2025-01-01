<?php
session_start(); // Ensure the session is started
// Check if user session variables are set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || $_SESSION['role'] !== 'ADMIN') {
    // Redirect to login page if not logged in
    header("Location: /furniture/login.php");
    exit; // Stop further execution
}

include 'C:\xampp\htdocs\furniture\includes\db.php';

// Initialize variables
$role_filter = isset($_GET['role']) ? $_GET['role'] : '';
$users = [];

// Fetch users with optional role filter
$query = "SELECT id, username, role, created_at FROM users";
$params = [];
if ($role_filter) {
    $query .= " WHERE role = ?";
    $params[] = $role_filter;
}
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param("s", ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
$stmt->close();
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
        <div class="mb-6">
            <h1 class="font-bold text-3xl mb-4">Users</h1>

            <!-- Role Filter Form -->
            <form method="GET" class="flex items-center justify-end gap-4 space-x-3">
                <select name="role" id="role" class="border border-gray-300 rounded p-2">
                    <option value="">All</option>
                    <option value="USER" <?= $role_filter === 'USER' ? 'selected' : '' ?>>User</option>
                    <option value="ADMIN" <?= $role_filter === 'ADMIN' ? 'selected' : '' ?>>Admin</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded">Filter</button>
            </form>
        </div>

        <!-- Users Table -->
        <div class=" rounded">
            <table class="w-full border-collapse text-left border border-gray-200">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 px-4">ID</th>
                        <th class="py-2 px-4">Username</th>
                        <th class="py-2 px-4">Role</th>
                        <th class="py-2 px-4">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($users) > 0): ?>
                        <?php foreach ($users as $user): ?>
                            <tr class="border-b">
                                <td class="py-2 px-4"><?= htmlspecialchars($user['id']); ?></td>
                                <td class="py-2 px-4"><?= htmlspecialchars($user['username']); ?></td>
                                <td class="py-2 px-4"><?= htmlspecialchars($user['role']); ?></td>
                                <td class="py-2 px-4"><?= htmlspecialchars($user['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>