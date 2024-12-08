<?php
// Include the database connection
include 'includes/db.php';

session_start(); // Start the session to manage user login state

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the username and password from POST request
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($username) || empty($password)) {
        $error = "Both username and password are required.";
    } else {
        // Prepare the SQL query to fetch user data
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect to the dashboard or admin panel based on role
                if ($user['role'] === 'ADMIN') {
                    header("Location: admin/dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
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
        <div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center py-6 px-4">
            <div class="max-w-md w-full">
                <div class="p-8 rounded-2xl bg-white shadow">
                    <h2 class="text-gray-800 text-center text-2xl font-bold">Log in</h2>

                    <!-- Display error message if login fails -->
                    <?php if (!empty($error)) : ?>
                        <div role="alert" class="rounded border-s-4 border-red-500 bg-red-50 p-4 my-2">
                            <p class="mt-2 text-sm text-red-700">
                                <?php echo $error; ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <form class="mt-8 space-y-4" method="POST" action="login.php">
                        <div>
                            <label class="text-gray-800 mb-2 block">User name</label>
                            <div class="relative flex items-center">
                                <input name="username" type="text" required class="w-full text-gray-800 border border-gray-300 px-4 py-3 rounded-md outline-blue-600" placeholder="Enter user name" />
                            </div>
                        </div>
                        <div>
                            <label class="text-gray-800 mb-2 block">Password</label>
                            <div class="relative flex items-center">
                                <input name="password" type="password" required class="w-full text-gray-800 border border-gray-300 px-4 py-3 rounded-md outline-blue-600" placeholder="Enter password" />
                            </div>
                        </div>
                        <div class="!mt-8">
                            <button type="submit" class="w-full py-3 px-4 tracking-wide rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                                Sign in
                            </button>
                        </div>
                        <p class="text-gray-800 !mt-8 text-center">Don't have an account? <a href="/furniture/register.php" class="text-blue-600 hover:underline ml-1 whitespace-nowrap font-semibold">Register here</a></p>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>

</html>