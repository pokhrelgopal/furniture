<?php
session_start();
include 'includes/db.php';
if (isset($_SESSION['username'])) {
    // If already logged in redirect to the dashboard or home page
    header('Location: /furniture/index.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = isset($_POST['role']) ? $_POST['role'] : 'USER'; // Default to 'USER'

    // Validate form data
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the username is already taken
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username is already taken.";
        } else {
            // Insert the new user into the database
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed_password, $role);

            if ($stmt->execute()) {
                // Redirect to login page after successful registration
                header("Location: login.php?success=1");
                exit;
            } else {
                $error = "Error: Could not register user.";
            }
        }

        $stmt->close();
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
    <main class="container mx-auto">
        <div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center py-6 px-4 my-10 ">
            <div class="max-w-md w-full">
                <div class="p-8 rounded-2xl bg-white shadow">
                    <h2 class="text-gray-800 text-center text-2xl font-bold">Create an Account</h2>
                    <?php if (!empty($error)) : ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <form class="mt-8 space-y-4" method="POST">
                        <div>
                            <label class="text-gray-800 mb-2 block">User name</label>
                            <input name="username" type="text" required class="w-full text-gray-800 border border-gray-300 px-4 py-3 rounded-md outline-blue-600" placeholder="Enter user name" />
                        </div>
                        <div>
                            <label class="text-gray-800 mb-2 block">Password</label>
                            <input name="password" type="password" required class="w-full text-gray-800 border border-gray-300 px-4 py-3 rounded-md outline-blue-600" placeholder="Enter password" />
                        </div>
                        <div>
                            <label class="text-gray-800 mb-2 block">Confirm Password</label>
                            <input name="confirm_password" type="password" required class="w-full text-gray-800 border border-gray-300 px-4 py-3 rounded-md outline-blue-600" placeholder="Re-enter password" />
                        </div>
                        <div class="!mt-8">
                            <button type="submit" class="w-full py-3 px-4 tracking-wide rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                                Register
                            </button>
                        </div>
                        <p class="text-gray-800 !mt-8 text-center">Already have an account? <a href="/furniture/login.php" class="text-blue-600 hover:underline ml-1 whitespace-nowrap font-semibold">Login</a></p>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>

</html>