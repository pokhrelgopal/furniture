<?php
include 'C:\xampp\htdocs\furniture\includes\db.php'; // Database connection

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Query to get total quantity of items in the cart for the user
    $cart_query = "SELECT SUM(quantity) AS total_quantity FROM cart WHERE user_id = $user_id";
    $cart_result = mysqli_query($conn, $cart_query);

    // If the query is successful, fetch the total quantity
    if ($cart_result) {
        $cart_data = mysqli_fetch_assoc($cart_result);
        $total_quantity = $cart_data['total_quantity'] ? $cart_data['total_quantity'] : 0;
    } else {
        $total_quantity = 0;
    }
} else {
    $total_quantity = 0;
}
?>


<header class="bg-gray-100">
    <div class="mx-auto container px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex-1 md:flex md:items-center md:gap-12">
                <a class="block text-teal-600 font-bold" href="/furniture/index.php">
                    .furniture
                </a>
            </div>

            <div class="md:flex md:items-center md:gap-12">
                <nav aria-label="Global" class="hidden md:block">
                    <ul class="flex items-center gap-6">
                        <li>
                            <a class="text-gray-500 transition hover:text-gray-500/75" href="/furniture/index.php"> Home </a>
                        </li>

                        <li>
                            <a class="text-gray-500 transition hover:text-gray-500/75" href="/furniture/furnitures.php"> Browse Furnitures </a>
                        </li>
                    </ul>
                </nav>

                <?php if (isset($_SESSION['user_id'])) : ?>
                    <!-- This div below is only visible if the user is logged in -->
                    <div class="hidden md:relative md:block">
                        <button
                            id="menu"
                            type="button"
                            class="overflow-hidden rounded-full shadow-inner p-2">
                            <span class="sr-only">Toggle dashboard menu</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>

                        </button>

                        <div id="menu__dropdown" class="hidden absolute end-0 z-10 mt-0.5 w-56 divide-y divide-gray-100 rounded-md border border-gray-100 bg-white shadow-lg" role="menu">
                            <div class="p-2">
                                <a href="/furniture/cart.php" class="block rounded-lg px-4 py-2 text-gray-500 hover:bg-gray-50 hover:text-gray-700" role="menuitem">My Cart
                                    (<?php echo $total_quantity; ?>)
                                </a>
                                <a href="/furniture/my-bookings.php" class="block rounded-lg px-4 py-2 text-gray-500 hover:bg-gray-50 hover:text-gray-700" role="menuitem">My Bookings</a>

                            </div>
                            <?php
                            if (isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN') {
                                echo '
                                <div class="p-2">
                                    <a href="/furniture/admin/dashboard.php" class="block rounded-lg px-4 py-2 text-gray-500 hover:bg-gray-50 hover:text-gray-700" role="menuitem">
                                        Admin Panel
                                    </a>
                                </div>
                                ';
                            }
                            ?>
                            <div class="p-2">
                                <!-- Logout Button -->
                                <form method="POST" action="/furniture/functions/logout.php">
                                    <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-4 py-2 text-red-700 hover:bg-red-50" role="menuitem">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php else : ?>
                    <!-- The div below is for not authenticated users -->
                    <div>
                        <a
                            class="inline-block rounded border border-indigo-600 bg-indigo-600 px-12 py-3 text-sm font-medium text-white hover:bg-transparent hover:text-indigo-600 focus:outline-none focus:ring active:text-indigo-500"
                            href="/furniture/login.php">
                            Login
                        </a>
                        <a
                            class="inline-block rounded border border-indigo-600 px-12 py-3 text-sm font-medium text-indigo-600 hover:bg-indigo-600 hover:text-white focus:outline-none focus:ring active:bg-indigo-500"
                            href="/furniture/register.php">
                            Register
                        </a>
                    </div>
                <?php endif; ?>

                <div class="block md:hidden">
                    <button class="rounded bg-gray-100 p-2 text-gray-600 transition hover:text-gray-600/75">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="size-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>
<script>
    const menu = document.getElementById('menu');
    const dropdown = document.getElementById('menu__dropdown');

    menu.addEventListener('click', () => {
        dropdown.classList.toggle('hidden');
    });
</script>