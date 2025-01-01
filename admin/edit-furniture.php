<?php
session_start();

// Ensure only admin users can access this page
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: /furniture/login.php");
    exit;
}

include 'C:\xampp\htdocs\furniture\includes\head.php';
include 'C:\xampp\htdocs\furniture\includes\db.php';

$furniture_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch existing furniture details
if ($furniture_id > 0) {
    $query = "SELECT * FROM furniture WHERE id = $furniture_id";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $furniture = mysqli_fetch_assoc($result);
    } else {
        $error = "Furniture not found!";
    }
}

// Fetch categories for the dropdown
$category_query = "SELECT id, name FROM category";
$category_result = mysqli_query($conn, $category_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $category_id = intval($_POST['category_id']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $image_name = $furniture['image_url']; // Default to current image

    // Validation
    $errors = [];

    if (empty($name)) {
        $errors[] = "Furniture name is required.";
    } elseif (strlen($name) > 255) {
        $errors[] = "Furniture name cannot exceed 255 characters.";
    }

    if ($category_id <= 0) {
        $errors[] = "Valid category selection is required.";
    }

    if (empty($description)) {
        $errors[] = "Description is required.";
    } elseif (strlen($description) > 500) {
        $errors[] = "Description cannot exceed 500 characters.";
    }

    if ($price <= 0) {
        $errors[] = "Price must be greater than 0.";
    }

    if ($stock < 0) {
        $errors[] = "Stock cannot be negative.";
    }

    // Handle new image upload
    if (!empty($_FILES['image']['name'])) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/furniture/uploads/furniture/';
        $unique_id = uniqid();
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_image_name = $unique_id . '.' . $file_extension;
        $upload_file = $upload_dir . $new_image_name;

        // Validate image type
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($image_tmp_name);
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Only JPEG, JPG, PNG, and GIF files are allowed.";
        }

        // Validate image size (max 5MB)
        $max_size = 5 * 1024 * 1024;
        if ($_FILES['image']['size'] > $max_size) {
            $errors[] = "File size exceeds 5MB.";
        }

        // Ensure upload directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Move uploaded file
        if (empty($errors) && move_uploaded_file($image_tmp_name, $upload_file)) {
            $image_name = $new_image_name;
        } else if (empty($errors)) {
            $errors[] = "Failed to upload image.";
        }
    }

    // Update furniture details in the database
    if (empty($errors)) {
        $update_query = "UPDATE furniture SET 
                         name = '" . mysqli_real_escape_string($conn, $name) . "', 
                         category_id = $category_id, 
                         description = '" . mysqli_real_escape_string($conn, $description) . "', 
                         price = $price, 
                         stock = $stock, 
                         image_url = '" . mysqli_real_escape_string($conn, $image_name) . "' 
                         WHERE id = $furniture_id";

        if (mysqli_query($conn, $update_query)) {
            $success = "Furniture updated successfully!";
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Furniture</title>
</head>

<body class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-72 bg-white shadow-sm">
        <?php include 'C:\xampp\htdocs\furniture\includes\sidebar.php'; ?>
    </aside>

    <!-- Main Content -->
    <main class="flex-1">
        <div class="container mx-auto my-10">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Edit Furniture</h1>

            <!-- Display error or success messages -->
            <?php if (!empty($errors)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <?php echo implode("<br>", $errors); ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($furniture)): ?>
                <form action="edit-furniture.php?id=<?php echo $furniture_id; ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <!-- Furniture Name -->
                    <div>
                        <label for="name" class="block font-medium text-gray-700">Furniture Name</label>
                        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($furniture['name']); ?>" required
                            class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-1 text-sm">
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block font-medium text-gray-700">Category</label>
                        <select name="category_id" id="category_id" required
                            class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-1 text-sm">
                            <option value="" disabled>Select a category</option>
                            <?php while ($row = mysqli_fetch_assoc($category_result)) : ?>
                                <option value="<?php echo $row['id']; ?>" <?php echo $furniture['category_id'] == $row['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($row['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" required rows="4"
                            class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-1 text-sm"><?php echo htmlspecialchars($furniture['description']); ?></textarea>
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block font-medium text-gray-700">Price</label>
                        <input min="1" type="number" name="price" id="price" value="<?php echo $furniture['price']; ?>" required step="0.01"
                            class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-1 text-sm">
                    </div>

                    <!-- Stock -->
                    <div>
                        <label for="stock" class="block font-medium text-gray-700">Stock</label>
                        <input min="0" type="number" name="stock" id="stock" value="<?php echo $furniture['stock']; ?>" required
                            class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-1 text-sm">
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label for="image" class="block font-medium text-gray-700">Furniture Image</label>
                        <input type="file" name="image" id="image" accept="image/*"
                            class="block w-full rounded border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-1 text-sm">
                        <p class="text-sm text-gray-500 mt-1">Current Image: <?php echo htmlspecialchars($furniture['image_url']); ?></p>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-2">
                        <button type="submit" name="submit"
                            class="w-full text-sm bg-blue-500 text-white py-2 px-4 rounded shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Update Furniture
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </main>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector("form");
        const nameInput = document.getElementById("name");
        const categoryInput = document.getElementById("category_id");
        const descriptionInput = document.getElementById("description");
        const priceInput = document.getElementById("price");
        const stockInput = document.getElementById("stock");
        const imageInput = document.getElementById("image");

        form.addEventListener("submit", function(event) {
            let isValid = true;
            let errorMessage = "";

            // Validate name: must contain letters
            const nameRegex = /[a-zA-Z]/; // At least one letter
            if (!nameRegex.test(nameInput.value) || nameInput.value.trim() === "") {
                isValid = false;
                errorMessage += "Furniture name must contain letters and cannot be only numbers or symbols.\n";
            }

            // Validate category
            if (!categoryInput.value) {
                isValid = false;
                errorMessage += "Please select a category.\n";
            }

            // Validate description: at least 10 characters and must contain letters
            const descriptionRegex = /[a-zA-Z]/; // At least one letter
            if (
                descriptionInput.value.trim().length < 10 ||
                !descriptionRegex.test(descriptionInput.value)
            ) {
                isValid = false;
                errorMessage += "Description must be at least 10 characters long and contain letters.\n";
            }

            // Validate price: positive number, not too high
            const price = parseFloat(priceInput.value);
            if (isNaN(price) || price <= 0 || price > 1e9) {
                isValid = false;
                errorMessage += "Price must be a positive number and cannot be too high (e.g., billions).\n";
            }

            // Validate stock: positive integer, not too high
            const stock = parseInt(stockInput.value, 10);
            if (isNaN(stock) || stock <= 0 || stock > 1e9) {
                isValid = false;
                errorMessage += "Stock must be a positive number and cannot be too high (e.g., billions).\n";
            }

            // Validate image: must be jpg, jpeg, png, or webp
            if (imageInput.files.length > 0) {
                const allowedTypes = ["image/jpeg", "image/png", "image/jpg", "image/webp"];
                const file = imageInput.files[0];

                if (!allowedTypes.includes(file.type)) {
                    isValid = false;
                    errorMessage += "Only JPG, JPEG, PNG, and WEBP files are allowed for the image.\n";
                }

                if (file.size > 5 * 1024 * 1024) {
                    isValid = false;
                    errorMessage += "Image size must not exceed 5MB.\n";
                }
            }

            // If invalid, prevent form submission and show error message
            if (!isValid) {
                event.preventDefault();
                alert(errorMessage);
            }
        });
    });
</script>


</html>