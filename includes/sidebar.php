<div class="flex h-screen flex-col justify-between border-e bg-white">
    <div class="px-4 py-6">
        <div class="flex-1 md:flex md:items-center md:gap-12">
            <a class="block text-teal-600 font-bold" href="/furniture/index.php">
                .furniture
            </a>
        </div>
        <ul class="mt-6 space-y-1">
            <?php
            // Get the current route
            $currentRoute = basename($_SERVER['REQUEST_URI']);
            ?>

            <li>
                <a href="/furniture/admin/dashboard.php"
                    class="block rounded-lg px-4 py-2 font-medium 
                    <?= $currentRoute === 'dashboard.php' ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700'; ?>">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="/furniture/admin/furnitures.php"
                    class="block rounded-lg px-4 py-2 font-medium 
                    <?= $currentRoute === 'furnitures.php' || $currentRoute === 'add-furniture.php' ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700'; ?>">
                    Furnitures
                </a>
            </li>
            <li>
                <a href="/furniture/admin/categories.php"
                    class="block rounded-lg px-4 py-2 font-medium 
                    <?= $currentRoute === 'categories.php' ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700'; ?>">
                    Categories
                </a>
            </li>
            <li>
                <a href="/furniture/admin/orders.php"
                    class="block rounded-lg px-4 py-2 font-medium 
                    <?= $currentRoute === 'orders.php' ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700'; ?>">
                    Orders
                </a>
            </li>
            <li>
                <a href="/furniture/admin/users.php"
                    class="block rounded-lg px-4 py-2 font-medium 
                    <?= $currentRoute === 'users.php' ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700'; ?>">
                    Users
                </a>
            </li>
        </ul>
    </div>

    <div class="sticky inset-x-0 bottom-0 border-t border-gray-100">
        <a href="#" class="flex items-center gap-2 bg-white p-4 hover:bg-gray-50">
            <button
                id="menu"
                type="button"
                class="overflow-hidden rounded-full shadow-inner p-2">
                <span class="sr-only">Toggle dashboard menu</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>

            </button>

            <div>
                <p class="text-xs">
                    <strong class="block font-medium">
                        <?php
                        // Check if username exists in session
                        if (isset($_SESSION['username'])) {
                            echo htmlspecialchars($_SESSION['username']); // Display username
                        } else {
                            echo "Guest"; // Fallback if user is not logged in
                        }
                        ?>
                    </strong>
                </p>
            </div>

        </a>
    </div>
</div>