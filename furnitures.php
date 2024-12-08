<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include __DIR__ . '/includes/head.php';    ?>
</head>

<body>
    <?php
    include './includes/navbar.php';    ?>
    <main class="container mx-auto my-10">
        <div class="flex min-h-screen">
            <aside class="w-64 bg-white p-4 border rounded h-fit">
                <h2 class="text-xl font-bold mb-4">Filters</h2>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Furniture Name</label>
                    <input type="text" class="w-full border p-2 text-sm" placeholder="Search by name ..." />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select class="w-full border p-2 text-sm">
                        <option>All Categories</option>
                        <option>Chairs</option>
                        <option>Tables</option>
                        <option>Sofas</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>
                    <input type="range" min="0" max="1000" class="w-full" />
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>$0</span>
                        <span>$1000</span>
                    </div>
                </div>
                <button class="w-full bg-gray-100 py-2 rounded hover:bg-gray-50 transition duration-300 text-sm">Apply Filters</Button>
            </aside>

            <main class="flex-1 pl-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white rounded border border-gray-100 shadow-sm overflow-hidden">
                        <img src="/furniture/assets/images/placeholder.jpg" alt="Modern Sofa" class="w-full h-48 object-cover" />
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-2">Modern Sofa</h3>
                            <p class="text-gray-600 mb-2 text-sm">Comfortable and stylish sofa for your living room</p>
                            <div class="flex justify-between items-center">
                                <span class="text-md font-semibold">$599.99</span>
                                <Button class="flex items-center gap-2 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="w-5 h-5">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                    </svg>
                                    Add to Cart
                                </Button>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded border border-gray-100 shadow-sm overflow-hidden">
                        <img src="/furniture/assets/images/placeholder.jpg" alt="Modern Sofa" class="w-full h-48 object-cover" />
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-2">Modern Sofa</h3>
                            <p class="text-gray-600 mb-2 text-sm">Comfortable and stylish sofa for your living room</p>
                            <div class="flex justify-between items-center">
                                <span class="text-md font-semibold">$599.99</span>
                                <Button class="flex items-center gap-2 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="w-5 h-5">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                    </svg>
                                    Add to Cart
                                </Button>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded border border-gray-100 shadow-sm overflow-hidden">
                        <img src="/furniture/assets/images/placeholder.jpg" alt="Modern Sofa" class="w-full h-48 object-cover" />
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-2">Modern Sofa</h3>
                            <p class="text-gray-600 mb-2 text-sm">Comfortable and stylish sofa for your living room</p>
                            <div class="flex justify-between items-center">
                                <span class="text-md font-semibold">$599.99</span>
                                <Button class="flex items-center gap-2 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="w-5 h-5">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                    </svg>
                                    Add to Cart
                                </Button>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded border border-gray-100 shadow-sm overflow-hidden">
                        <img src="/furniture/assets/images/placeholder.jpg" alt="Modern Sofa" class="w-full h-48 object-cover" />
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-2">Modern Sofa</h3>
                            <p class="text-gray-600 mb-2 text-sm">Comfortable and stylish sofa for your living room</p>
                            <div class="flex justify-between items-center">
                                <span class="text-md font-semibold">$599.99</span>
                                <Button class="flex items-center gap-2 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="w-5 h-5">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                    </svg>
                                    Add to Cart
                                </Button>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded border border-gray-100 shadow-sm overflow-hidden">
                        <img src="/furniture/assets/images/placeholder.jpg" alt="Modern Sofa" class="w-full h-48 object-cover" />
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-2">Modern Sofa</h3>
                            <p class="text-gray-600 mb-2 text-sm">Comfortable and stylish sofa for your living room</p>
                            <div class="flex justify-between items-center">
                                <span class="text-md font-semibold">$599.99</span>
                                <Button class="flex items-center gap-2 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="w-5 h-5">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                    </svg>
                                    Add to Cart
                                </Button>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded border border-gray-100 shadow-sm overflow-hidden">
                        <img src="/furniture/assets/images/placeholder.jpg" alt="Modern Sofa" class="w-full h-48 object-cover" />
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-2">Modern Sofa</h3>
                            <p class="text-gray-600 mb-2 text-sm">Comfortable and stylish sofa for your living room</p>
                            <div class="flex justify-between items-center">
                                <span class="text-md font-semibold">$599.99</span>
                                <Button class="flex items-center gap-2 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="w-5 h-5">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                    </svg>
                                    Add to Cart
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </main>
</body>

</html>