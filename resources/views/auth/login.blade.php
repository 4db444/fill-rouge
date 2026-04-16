@extends('layout.base')
@section("main")
    <main class="flex-1 flex items-center justify-center px-4 py-12 sm:py-20">
        <div class="w-full max-w-sm">
            <h2 class="text-2xl font-semibold text-black text-center mb-8">Log in</h2>
            <form method="POST" action="/auth/login" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
                </div>

                <button type="submit" class="w-full bg-black text-white text-sm font-medium py-2.5 rounded-md hover:bg-gray-800 transition-colors">Log in</button>
            </form>
            <p class="text-center text-sm text-gray-500 mt-6">Don't have an account? <a href="/auth/signup" class="text-black font-medium hover:underline">Sign up</a></p>
        </div>
    </main>
@endsection
