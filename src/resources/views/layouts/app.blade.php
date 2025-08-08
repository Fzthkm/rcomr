<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RCOMR')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">
<nav class="bg-white shadow-md py-4 px-6 mb-6">
    <div class="container mx-auto flex justify-between">
        <a href="/" class="text-xl font-bold text-indigo-600">RCOMR</a>
        <!-- Навигация, если нужна -->
    </div>
</nav>

<main class="container mx-auto">
    @yield('content')
</main>
</body>
</html>
