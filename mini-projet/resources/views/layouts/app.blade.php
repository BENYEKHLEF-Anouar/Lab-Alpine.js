<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel Alpine Lab</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">

    <!-- Vite Assets (Tailwind + Alpine.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-50 text-slate-800 antialiased font-sans selection:bg-indigo-100 selection:text-indigo-700">

    <div class="min-h-screen relative">
        <header class="sticky top-0 z-40 w-full bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="text-indigo-600">
                        <i class="fa-solid fa-layer-group text-xl"></i>
                    </div>
                    <h1 class="text-xl font-bold tracking-tight text-slate-900">
                        Laravel <span class="text-indigo-600">Alpine Lab</span>
                    </h1>
                </div>
                <nav>
                    <a href="{{ route('articles.index') }}"
                        class="px-4 py-2 rounded-md text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-gray-50 transition-all duration-200">
                        Articles
                    </a>
                </nav>
            </div>
        </header>

        <main class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    </div>

</body>

</html>