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

        <!-- Furniture Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 my-10">

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
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300"> <?php echo $row['category_name']; ?></span>

                            <h3 class="text-lg font-semibold mb-2"><?php echo $row['name']; ?></h3>
                            <p class="text-gray-600 mb-2 text-sm truncate">
                                <?php echo $row['description']; ?>
                            </p>
                            <div class="flex justify-between items-center">
                                <span class="text-md font-semibold">Rs. <?php echo number_format($row['price'], 2); ?></span>
                                <span class="bg-blue-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"> <?php echo $row['stock']; ?> in Stock</span>
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