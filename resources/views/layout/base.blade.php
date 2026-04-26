<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Splity</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(["resources/css/app.css"])
</head>
<body class="bg-white text-gray-900 font-sans min-h-screen flex flex-col relative">
    <header class="border-b border-gray-200 bg-white relative z-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <a href="{{ url('/dashboard') }}" class="text-lg font-semibold tracking-tight text-black">Splity</a>
            @auth
                {{-- Desktop navigation --}}
                <div class="hidden md:flex items-center gap-4">
                    <a href="{{ url('/profile') }}" class="text-sm text-gray-600 hover:text-black transition-colors">Profile</a>
                    <a href="{{ route('groups.index') }}" class="text-sm text-gray-600 hover:text-black transition-colors">Groups</a>
                    <a href="{{ route('requests.index') }}" class="text-sm text-gray-600 hover:text-black transition-colors">Requests</a>
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-600 hover:text-black transition-colors">Admin</a>
                    @endif
                    @if (in_array(auth()->user()->role, ['admin', 'moderator']))
                        <a href="{{ route('moderator.dashboard') }}" class="text-sm text-gray-600 hover:text-black transition-colors">Moderator</a>
                    @endif
                    <form action="{{ route("logout") }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-black transition-colors">Logout</button>
                    </form>
                </div>

                {{-- Mobile hamburger button --}}
                <button id="mobile-menu-btn" type="button" class="md:hidden flex flex-col justify-center items-center w-8 h-8 gap-1.5 group" aria-label="Toggle menu" aria-expanded="false">
                    <span class="block w-5 h-0.5 bg-gray-700 transition-all duration-300 origin-center group-[.open]:translate-y-[8px] group-[.open]:rotate-45"></span>
                    <span class="block w-5 h-0.5 bg-gray-700 transition-all duration-300 group-[.open]:opacity-0"></span>
                    <span class="block w-5 h-0.5 bg-gray-700 transition-all duration-300 origin-center group-[.open]:-translate-y-[8px] group-[.open]:-rotate-45"></span>
                </button>
            @endauth
        </div>

        {{-- Mobile dropdown menu --}}
        @auth
            <div id="mobile-menu" class="md:hidden hidden border-t border-gray-100 bg-white overflow-hidden transition-all duration-300 ease-in-out" style="max-height: 0;">
                <div class="px-4 py-3 space-y-1">
                    <a href="{{ url('/profile') }}" class="block px-3 py-2.5 text-sm text-gray-600 hover:text-black hover:bg-gray-50 rounded-md transition-colors">Profile</a>
                    <a href="{{ route('groups.index') }}" class="block px-3 py-2.5 text-sm text-gray-600 hover:text-black hover:bg-gray-50 rounded-md transition-colors">Groups</a>
                    <a href="{{ route('requests.index') }}" class="block px-3 py-2.5 text-sm text-gray-600 hover:text-black hover:bg-gray-50 rounded-md transition-colors">Requests</a>
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2.5 text-sm text-gray-600 hover:text-black hover:bg-gray-50 rounded-md transition-colors">Admin</a>
                    @endif
                    @if (in_array(auth()->user()->role, ['admin', 'moderator']))
                        <a href="{{ route('moderator.dashboard') }}" class="block px-3 py-2.5 text-sm text-gray-600 hover:text-black hover:bg-gray-50 rounded-md transition-colors">Moderator</a>
                    @endif
                    <div class="pt-1 border-t border-gray-100 mt-1">
                        <form action="{{ route("logout") }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2.5 text-sm text-gray-500 hover:text-black hover:bg-gray-50 rounded-md transition-colors">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        @endauth
    </header>

    <div class="flex-1">
        @yield("main")
    </div>

    <footer class="border-t border-gray-200 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-6">
            <p class="text-center text-xs text-gray-400">&copy; {{ date('Y') }} Splity. All rights reserved.</p>
        </div>
    </footer>

    <ul class="fixed pointer-events-none top-0 right-0 h-screen max-w-[400px] w-full py-[80px] px-2">
        @if (session('success'))
            <div class="mb-6 px-4 py-3 bg-green-50 text-green-700 text-sm rounded-md border border-green-200">
                {{ session('success') }}
            </div>
            @endif
            
        @if($errors->any())
            @foreach ($errors->all() as $err)
                <div class="mb-6 px-4 py-3 bg-red-50 text-red-700 text-sm rounded-md border border-red-200">
                    {{ $err }}
                </div>
            @endforeach
        @endif
    </ul>

    {{-- Mobile menu toggle script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');
            if (!btn || !menu) return;

            btn.addEventListener('click', function () {
                const isOpen = btn.classList.contains('open');
                if (isOpen) {
                    // Close
                    btn.classList.remove('open');
                    btn.setAttribute('aria-expanded', 'false');
                    menu.style.maxHeight = '0';
                    setTimeout(() => menu.classList.add('hidden'), 300);
                } else {
                    // Open
                    btn.classList.add('open');
                    btn.setAttribute('aria-expanded', 'true');
                    menu.classList.remove('hidden');
                    // Let browser paint, then animate
                    requestAnimationFrame(() => {
                        menu.style.maxHeight = menu.scrollHeight + 'px';
                    });
                }
            });
        });
    </script>

    @yield("script")
</body>
</html>