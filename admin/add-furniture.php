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
        $stock = intval($_POST['stock']);

        // Handle image upload
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/furniture/uploads/furniture/';
        $unique_id = uniqid();
        $file_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $new_image_name = $unique_id . '.' . $file_extension;
        $upload_file = $upload_dir . $new_image_name;

        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $file_type = mime_content_type($image_tmp_name);

        if (!in_array($file_type, $allowed_types)) {
            $error = "Error: Only JPEG, JPG, PNG, and WEBP files are allowed.";
        }

        $max_size = 5 * 1024 * 1024; // 5MB
        if ($_FILES['image']['size'] > $max_size) {
            $error = "Error: File size exceeds the maximum limit of 5MB.";
        }

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (move_uploaded_file($image_tmp_name, $upload_file)) {
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
    <aside class="w-72 bg-white shadow-sm">
        <?php include 'C:\xampp\htdocs\furniture\includes\sidebar.php'; ?>
    </aside>

    <main class="flex-1">
        <div class="container mx-auto my-10">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Add Furniture</h1>

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

            <form action="add-furniture.php" method="POST" enctype="multipart/form-data" class="space-y-4" id="furnitureForm">
                <div>
                    <label for="name" class="block font-medium text-gray-700">Furniture Name</label>
                    <input type="text" name="name" id="name" required
                        class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-1 text-sm">
                </div>

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

                <div>
                    <label for="description" class="block font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" required rows="4"
                        class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-1 text-sm"></textarea>
                </div>

                <div>
                    <label for="price" class="block font-medium text-gray-700">Price</label>
                    <input type="number" name="price" id="price" required step="0.01"
                        class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-1 text-sm">
                </div>

                <div>
                    <label for="stock" class="block font-medium text-gray-700">Stock</label>
                    <input type="number" min="1" name="stock" id="stock" required
                        class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-1 text-sm">
                </div>

                <div>
                    <label for="image" class="block font-medium text-gray-700">Furniture Image</label>
                    <input type="file" name="image" id="image" accept="image/*" required
                        class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-1 text-sm">
                </div>

                <div class="mt-2">
                    <button type="submit" name="submit"
                        class="w-full text-sm bg-blue-500 text-white py-2 px-4 rounded shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Add Furniture
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        document.getElementById('furnitureForm').addEventListener('submit', function(event) {
            let errors = [];

            const name = document.getElementById('name').value.trim();
            const description = document.getElementById('description').value.trim();
            const price = parseFloat(document.getElementById('price').value.trim());
            const stock = parseInt(document.getElementById('stock').value.trim());
            const image = document.getElementById('image').files[0];

            // Validate name
            if (!/[a-zA-Z]/.test(name) || /^\d+$/.test(name) || /^[^a-zA-Z]+$/.test(name)) {
                errors.push('Furniture name must contain letters and cannot have only numbers or symbols.');
            }

            // Validate description
            if (description.length < 10 || !/[a-zA-Z]/.test(description)) {
                errors.push('Description must be at least 10 characters long and include letters.');
            }

            // Validate price
            if (isNaN(price) || price <= 0 || price > 1e9) {
                errors.push('Price must be a positive number and cannot be excessively high.');
            }

            // Validate stock
            if (isNaN(stock) || stock <= 0 || stock > 1e9) {
                errors.push('Stock must be a positive number and cannot be excessively high.');
            }

            // Validate image
            if (image) {
                const allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                const fileExtension = image.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(fileExtension)) {
                    errors.push('Image must be in JPG, JPEG, PNG, or WEBP format.');
                }
            } else {
                errors.push('Image is required.');
            }

            // Display errors and prevent submission
            if (errors.length > 0) {
                alert(errors.join('\n'));
                event.preventDefault();
            }
        });
    </script>
</body>

</html>