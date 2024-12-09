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
    <?php include 'C:\xampp\htdocs\furniture\includes\db.php';
    $query = "SELECT id, name FROM category";
    $result = mysqli_query($conn, $query);
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        // Retrieve form data
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $category_id = intval($_POST['category_id']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);  // Added stock field

        // Handle image upload
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/furniture/uploads/furniture/';
        // Generate a unique name for the file
        $unique_id = uniqid(); // Generates a unique ID based on the current time in microseconds
        $file_extension = pathinfo($image_name, PATHINFO_EXTENSION); // Extract file extension
        $new_image_name = $unique_id . '.' . $file_extension;
        $upload_file = $upload_dir . $new_image_name;

        // Validate image type
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($image_tmp_name);

        if (!in_array($file_type, $allowed_types)) {
            $error = "Error: Only JPEG, JPG, PNG, and GIF files are allowed.";
        }

        // Validate image size (e.g., max 5MB)
        $max_size = 5 * 1024 * 1024; // 5MB
        if ($_FILES['image']['size'] > $max_size) {
            $error = "Error: File size exceeds the maximum limit of 5MB.";
        }

        // Ensure upload directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Move file to the uploads directory with the unique name
        if (move_uploaded_file($image_tmp_name, $upload_file)) {
            // Insert data into the database
            $query = "INSERT INTO furniture (name, description, price, stock, image_url, category_id) 
                      VALUES ('$name', '$description', '$price', '$stock', '$new_image_name', '$category_id')";

            if (mysqli_query($conn, $query)) {
                $success = "Furniture added successfully!";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        } else {
            $error = "Error: Failed to upload the image.";
        }
    }
    ?>

</head>

<body class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-72 bg-white shadow-sm">
        <?php include 'C:\xampp\htdocs\furniture\includes\sidebar.php'; ?>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ">
        <div class="container mx-auto my-10">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Add Furniture</h1>

            <!-- Display error or success message -->
            <?php if (!empty($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form action="add-furniture.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <!-- Furniture Name -->
                <div>
                    <label for="name" class="block font-medium text-gray-700">Furniture Name</label>
                    <input type="text" name="name" id="name" required
                        class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-1 text-sm">
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block font-medium text-gray-700">Category</label>
                    <select name="category_id" id="category_id" required
                        class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-1 text-sm">
                        <option value="" disabled selected>Choose a category</option>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['id']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" required rows="4"
                        class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-1 text-sm"></textarea>
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block font-medium text-gray-700">Price</label>
                    <input type="number" name="price" id="price" required step="0.01"
                        class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-1 text-sm">
                </div>

                <!-- Stock -->
                <div>
                    <label for="stock" class="block font-medium text-gray-700">Stock</label>
                    <input type="number" name="stock" id="stock" required
                        class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-1 text-sm">
                </div>

                <!-- Image Upload -->
                <div>
                    <label for="image" class="block font-medium text-gray-700">Furniture Image</label>
                    <input type="file" name="image" id="image" accept="image/*" required
                        class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-1 text-sm">
                </div>

                <!-- Submit Button -->
                <div class="mt-2">
                    <button type="submit" name="submit"
                        class="w-full text-sm bg-blue-500 text-white py-2 px-4 rounded shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Add Furniture
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>