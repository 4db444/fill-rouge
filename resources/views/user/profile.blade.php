@extends("layout.base")

@section("main")
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <h1 class="text-2xl font-semibold text-black mb-6">Profile</h1>

        @if ($user->id === auth()->user()->id)
            <div class="space-y-6">
                {{-- Update Info --}}
                <div class="border border-gray-200 rounded-lg p-4 sm:p-6 bg-white">
                    <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">Personal Information</h2>
                    <form action="{{route("user.info.update", $user->id)}}" method="POST" class="space-y-4">
                        @method("PUT")
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First name</label>
                                <input type="text" id="first_name" name="first_name" value="{{$user->first_name}}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last name</label>
                                <input type="text" id="last_name" name="last_name" value="{{$user->last_name}}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
                            </div>
                        </div>
                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                            <textarea name="bio" id="bio" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors resize-vertical">{{$user->bio}}</textarea>
                        </div>
                        <div class="flex justify-end">
                            <input type="submit" value="Save changes" class="bg-black text-white text-sm font-medium px-6 py-2 rounded-md hover:bg-gray-800 transition-colors cursor-pointer">
                        </div>
                    </form>
                </div>

                {{-- Update Password --}}
                <div class="border border-gray-200 rounded-lg p-4 sm:p-6 bg-white">
                    <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">Change Password</h2>
                    <form action="{{route("user.password.update", $user->id)}}" method="POST" class="space-y-4">
                        @method("put")
                        <div>
                            <label for="old_password" class="block text-sm font-medium text-gray-700 mb-1">Current password</label>
                            <input type="password" id="old_password" name="old_password" placeholder="••••••••" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
                        </div>
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New password</label>
                            <input type="password" id="new_password" name="new_password" placeholder="••••••••" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
                        </div>
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm new password</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="••••••••" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-black text-white text-sm font-medium px-6 py-2 rounded-md hover:bg-gray-800 transition-colors">Update password</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mt-6 border border-gray-300 rounded-lg p-4 bg-gray-50">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $err)
                            <li class="text-sm text-gray-700">• {{$err}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

        @endif
    </main>
@endsection