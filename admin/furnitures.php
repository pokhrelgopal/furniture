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
    <?php include 'C:\xampp\htdocs\furniture\includes\db.php'; ?>
</head>

<body class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-72 bg-white shadow-sm">
        <?php include 'C:\xampp\htdocs\furniture\includes\sidebar.php'; ?>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <div class="flex items-center justify-between">
            <h1 class="font-bold text-3xl">Furnitures</h1>
            <a href="/furniture/admin/add-furniture.php">
                <button class="bg-blue-500 text-sm hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                    Add Furniture
                </button>
            </a>
        </div>
        <?php
        if (isset($_SESSION['message'])) {
            $message_type = $_SESSION['message_type'] ?? 'success';

            // Define the styling for success and error
            $alert_classes = [
                'success' => 'bg-green-100 border border-green-400 text-green-700',
                'error' => 'bg-red-100 border border-red-400 text-red-700',
            ];

            // Use the appropriate class or default to success
            $classes = $alert_classes[$message_type] ?? $alert_classes['success'];

            // Display the alert
            echo '<div class="' . $classes . ' px-4 py-3 rounded relative mb-4 mt-4">';
            echo htmlspecialchars($_SESSION['message']);
            echo '</div>';

            // Clear the message from the session
            unset($_SESSION['message'], $_SESSION['message_type']);
        }
        ?>

        <!-- Furniture Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 my-5">

            <?php
            // Fetch furniture data from the database
            $query = "SELECT f.id, f.name, f.description, f.price, f.stock, f.image_url, c.name AS category_name 
                      FROM furniture f
                      LEFT JOIN category c ON f.category_id = c.id";
            $result = mysqli_query($conn, $query);

            // Check if there are results
            if (mysqli_num_rows($result) > 0) {
                // Loop through the fetched results
                while ($row = mysqli_fetch_assoc($result)) {
                    // var_dump($row);
                    $image_url = $row['image_url'] ? "/furniture/uploads/furniture/" . $row['image_url'] : "/furniture/assets/images/placeholder.jpg";
            ?>
                    <div class="bg-white rounded border border-gray-100 shadow-sm overflow-hidden">
                        <img src="<?php echo $image_url; ?>" alt="<?php echo $row['name']; ?>" class="w-full h-48 object-cover" />
                        <div class="p-4">
                            <p class="flex items-center justify-between mb-2">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300"> <?php echo $row['category_name']; ?></span>
                                <span class="bg-blue-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"> <?php echo $row['stock']; ?> in Stock</span>
                            </p>
                            <h3 class="text-lg font-semibold mb-2"><?php echo $row['name']; ?></h3>
                            <p class="text-gray-600 mb-2 text-sm truncate">
                                <?php echo $row['description']; ?>
                            </p>
                            <div class="flex justify-between items-center">
                                <p class="text-md font-semibold">Rs. <?php echo number_format($row['price'], 2); ?></span>
                                <div class="flex items-center">
                                    <a href="/furniture/admin/edit-furniture.php?id=<?php echo $row['id']; ?>">
                                        <button
                                            class="inline-block p-2 text-gray-700 bg-green-100  focus:relative"
                                            title="Edit Furniture">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                                class="size-4">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </button>
                                    </a>
                                    <a href="/furniture/admin/delete-furniture.php?id=<?php echo $row['id']; ?>">
                                        <button
                                            class="inline-block p-2 text-gray-700 bg-red-100 focus:relative"
                                            title="Delete Furniture">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                                class="size-4">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </a>

                                </div>
                            </div>

                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<p class="col-span-full text-center text-gray-500">No furniture items found.</p>';
            }
            ?>
        </div>
    </main>
</body>

</html>