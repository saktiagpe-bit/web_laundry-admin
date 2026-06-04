<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BubbleWash - Laundry Pink Cute</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <style>
        body { font-family: 'Fredoka', sans-serif; }
        .pink-gradient { background: linear-gradient(135deg, #FFB6C1 0%, #FFC0CB 100%); }
        .text-pink-dark { color: #D81B60; }
        .bg-pink-soft { background-color: #FFF0F5; }
        .btn-pink { background-color: #FF69B4; color: white; transition: all 0.3s; }
        .btn-pink:hover { background-color: #FF1493; transform: scale(1.05); }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-pink-soft text-gray-800 antialiased min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-pink-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <i data-feather="cloud-snow" class="text-pink-500 w-8 h-8"></i>
                        <span class="font-bold text-2xl text-pink-dark">BubbleWash</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('services.index') }}" class="text-gray-600 hover:text-pink-500 font-medium">Layanan</a>
                    @auth
                        @if(Auth::user()->phone_verified_at)
                        <a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-pink-500 relative">
                            <i data-feather="shopping-bag"></i>
                            @if(session('cart'))
                                <span class="absolute -top-2 -right-2 bg-pink-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ count(session('cart')) }}</span>
                            @endif
                        </a>
                        <a href="{{ route('dashboard.index') }}" class="text-gray-600 hover:text-pink-500 font-medium">Dashboard</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-pink-500 font-medium">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-pink-500 font-medium">Login</a>
                        <a href="{{ route('register') }}" class="btn-pink px-4 py-2 rounded-full font-medium shadow-md">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Alerts -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4 rounded shadow-sm max-w-7xl mx-auto w-full" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4 rounded shadow-sm max-w-7xl mx-auto w-full" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif
    @if (session('warning'))
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 m-4 rounded shadow-sm max-w-7xl mx-auto w-full" role="alert">
            <p>{{ session('warning') }}</p>
        </div>
    @endif
    @if (session('simulated_otp'))
        <div class="bg-pink-100 border-l-4 border-pink-500 text-pink-700 p-4 m-4 rounded shadow-sm max-w-7xl mx-auto w-full flex items-center gap-3 animate-bounce" role="alert">
            <i data-feather="message-square"></i>
            <p class="font-bold">{{ session('simulated_otp') }}</p>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4 rounded shadow-sm max-w-7xl mx-auto w-full">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-pink-200 mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-500">
            <p>&copy; {{ date('Y') }} BubbleWash Laundry. Cute & Professional Services.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
      feather.replace()
    </script>
    @stack('scripts')
</body>
</html>
