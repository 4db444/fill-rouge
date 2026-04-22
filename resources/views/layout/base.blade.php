<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'MyApp') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(["resources/css/app.css"])
</head>
<body class="bg-white text-gray-900 font-sans min-h-screen flex flex-col relative">
    <header class="border-b border-gray-200 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <a href="{{ url('/dashboard') }}" class="text-lg font-semibold tracking-tight text-black">myapp</a>
            @auth
                <div class="flex items-center gap-4">
                    <a href="{{ url('/profile') }}" class="text-sm text-gray-600 hover:text-black transition-colors">Profile</a>
                    <a href="{{ route('groups.index') }}" class="text-sm text-gray-600 hover:text-black transition-colors">Groups</a>
                    <a href="{{ route('requests.index') }}" class="text-sm text-gray-600 hover:text-black transition-colors">Requests</a>
                    <form action="{{ route("logout") }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-black transition-colors">Logout</button>
                    </form>
                </div>
            @endauth
        </div>
    </header>

    <div class="flex-1">
        @yield("main")
    </div>

    <footer class="border-t border-gray-200 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-6">
            <p class="text-center text-xs text-gray-400">&copy; {{ date('Y') }} myapp. All rights reserved.</p>
        </div>
    </footer>

    <ul class="fixed pointer-events-none top-0 right-0 h-screen bg-red-500/10 w-[400px]">
        @yield("logs")
    </ul>

    @yield("script")
</body>
</html>