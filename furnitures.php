<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'C:\xampp\htdocs\furniture\includes\head.php'; ?>
    <?php include 'C:\xampp\htdocs\furniture\includes\db.php'; ?>
</head>

<body>
    <?php include './includes/navbar.php'; ?>

    <main class="container mx-auto my-10">
        <div class="flex min-h-screen">
            <aside class="w-64 bg-white p-4 border rounded h-fit">
                <h2 class="text-xl font-bold mb-4">Filters</h2>

                <!-- Search by Furniture Name -->
                <form method="GET" action="" class="mb-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Furniture Name</label>
                        <input type="text" name="search_name" class="w-full border p-2 text-sm" placeholder="Search by name ..." value="<?php echo isset($_GET['search_name']) ? $_GET['search_name'] : ''; ?>" />
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category" class="w-full border p-2 text-sm">
                            <option value="">All Categories</option>
                            <?php
                            // Fetch categories from the database
                            $category_query = "SELECT id, name FROM category";
                            $category_result = mysqli_query($conn, $category_query);
                            while ($category = mysqli_fetch_assoc($category_result)) {
                                // Check if the category is selected
                                $selected = (isset($_GET['category']) && $_GET['category'] == $category['id']) ? 'selected' : '';
                                echo "<option value='{$category['id']}' $selected>{$category['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>
                        <input type="range" name="price_range" min="0" max="100000" class="w-full" value="<?php echo isset($_GET['price_range']) ? $_GET['price_range'] : 1000; ?>" id="priceRange" />
                        <div class="flex justify-between items-center text-sm text-gray-600">
                            <span>Rs. 0</span>
                            <span id="currentValue" class="ml-2"><?php echo isset($_GET['price_range']) ? $_GET['price_range'] : 1000; ?></span>
                            <span>Rs. 100000</span>
                        </div>
                    </div>

                    <script>
                        // Get the range input and the span to show the value
                        const priceRange = document.getElementById('priceRange');
                        const currentValue = document.getElementById('currentValue');

                        // Update the current value when the slider is moved
                        priceRange.addEventListener('input', function() {
                            currentValue.textContent = 'Rs. ' + priceRange.value;
                        });
                    </script>


                    <button type="submit" class="w-full bg-gray-100 py-2 rounded hover:bg-gray-50 transition duration-300 text-sm">Apply Filters</button>
                </form>
            </aside>

            <main class="flex-1 pl-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    <?php
                    // Get search and filter parameters
                    $search_name = isset($_GET['search_name']) ? $_GET['search_name'] : '';
                    $category_id = isset($_GET['category']) ? $_GET['category'] : '';
                    $price_range = isset($_GET['price_range']) ? $_GET['price_range'] : 100000;

                    // Build the query with filters
                    $query = "SELECT f.id, f.name, f.description, f.price, f.stock, f.image_url, c.name AS category_name 
        FROM furniture f
        LEFT JOIN category c ON f.category_id = c.id
        WHERE 1=1";

                    // Apply search filter for furniture name
                    if (!empty($search_name)) {
                        $query .= " AND f.name LIKE '%" . mysqli_real_escape_string($conn, $search_name) . "%'";
                    }

                    // Apply category filter
                    if (!empty($category_id)) {
                        $query .= " AND f.category_id = " . mysqli_real_escape_string($conn, $category_id);
                    }

                    // Apply price range filter
                    if (!empty($price_range)) {
                        $query .= " AND f.price <= " . mysqli_real_escape_string($conn, $price_range);
                    }

                    // Execute the query
                    $result = mysqli_query($conn, $query);

                    // Check if there are results
                    if (mysqli_num_rows($result) > 0) {
                        // Loop through the fetched results
                        while ($row = mysqli_fetch_assoc($result)) {
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

                                        <button type="button" class="px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add to Cart</button>

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
        </div>
    </main>
</body>

</html>