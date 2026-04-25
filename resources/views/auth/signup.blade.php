@extends("layout.base")
@section("main")
    <main class="flex-1 flex items-center justify-center px-4 py-12 sm:py-20">
        <div class="w-full max-w-sm">
            <h2 class="text-2xl font-semibold text-black text-center mb-8">Create an account</h2>
            <form action="/auth/signup" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First name</label>
                        <input type="text" id="first_name" name="first_name" placeholder="John" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last name</label>
                        <input type="text" id="last_name" name="last_name" placeholder="Doe" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
                    </div>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input type="text" id="city" name="city" placeholder="Agadir" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
                    </div>
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <input type="text" id="country" name="country" placeholder="Morocco" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
                    </div>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
                </div>

                <button type="submit" class="w-full bg-black text-white text-sm font-medium py-2.5 rounded-md hover:bg-gray-800 transition-colors">Sign up</button>
            </form>
            <p class="text-center text-sm text-gray-500 mt-6">Already have an account? <a href="/auth/login" class="text-black font-medium hover:underline">Log in</a></p>
        </div>
    </main>
@endsection

@section("logs")
    @if ($errors->any())
        @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    @endif
@endsection