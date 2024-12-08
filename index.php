<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include __DIR__ . '/includes/head.php'; ?>
</head>

<body>
    <?php include './includes/navbar.php'; ?>
    <div class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-sky-50 via-indigo-100 to-cyan-100">
        <div class="absolute inset-0 bg-grid-slate-200/50 bg-[size:30px_30px] [mask-image:radial-gradient(ellipse_80%_80%_at_50%_50%,#000_20%,transparent_100%)]"></div>
        <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8">
            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-gray-800 mb-6">
                Rent <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-500">Beautiful</span> Furnitures
            </h1>
            <p class="max-w-2xl mx-auto text-xl sm:text-2xl text-gray-600 mb-10">
                Elevate your space effortlessly. Rent designer furniture for your home or office, hassle-free.
            </p>
            <a href="/furniture/furnitures.php"><button class="bg-gradient-to-r from-blue-400 to-cyan-500 hover:from-blue-500 hover:to-cyan-600 text-white font-bold py-3 px-8 rounded-full text-lg shadow-lg transition-all duration-300 hover:scale-105">
                    Explore Collection
                </button></a>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-white to-transparent"></div>
        <div class="absolute -top-40 -left-40 w-80 h-80 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
        <div class="absolute -bottom-40 -right-40 w-80 h-80 bg-cyan-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-indigo-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
    </div>
</body>

</html>