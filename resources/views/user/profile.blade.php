@extends("layout.base")

@section("main")
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <h1 class="text-2xl font-semibold text-black mb-6">Profile</h1>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-6 border border-gray-200 rounded-lg p-4 bg-gray-50">
                <p class="text-sm text-gray-700">{{ session('success') }}</p>
            </div>
        @endif

        @if ($user->id === auth()->user()->id)
            {{-- ═══════════════════════════════════
                 OWN PROFILE — Edit Forms
            ════════════════════════════════════ --}}
            <div class="space-y-6">
                {{-- Update Info --}}
                <div class="border border-gray-200 rounded-lg p-4 sm:p-6 bg-white">
                    <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">Personal Information</h2>
                    <form action="{{route("user.info.update", $user->id)}}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method("PUT")

                        {{-- Profile Image --}}
                        <div class="flex items-center gap-4">
                            <img 
                                id="profile-image-preview"
                                src="{{ asset($user->profile->img_url ?? 'storage/images/profiles/default.png') }}" 
                                class="w-16 h-16 rounded-full object-cover border-2 border-gray-200"
                                alt="{{ $user->first_name }}">
                            <div>
                                <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-1">Profile image</label>
                                <input type="file" id="profile_image" name="profile_image" accept="image/jpg,image/jpeg,image/png,image/webp" class="text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border file:border-gray-300 file:text-xs file:font-medium file:bg-white file:text-gray-700 hover:file:bg-gray-50 file:cursor-pointer file:transition-colors">
                            </div>
                        </div>

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
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" id="city" name="city" value="{{$user->city}}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
                            </div>
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                <input type="text" id="country" name="country" value="{{$user->country}}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
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
                        @csrf
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

        @else
            {{-- ═══════════════════════════════════
                 OTHER USER'S PROFILE — Read Only
            ════════════════════════════════════ --}}
            <div class="space-y-6">
                {{-- Profile Card --}}
                <div class="border border-gray-200 rounded-lg p-6 sm:p-8 bg-white">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                        <img 
                            src="{{ asset($user->profile->img_url ?? 'storage/images/profiles/default.png') }}" 
                            class="w-20 h-20 rounded-full object-cover border-2 border-gray-200"
                            alt="{{ $user->first_name }}">
                        <div class="text-center sm:text-left flex-1">
                            <h2 class="text-xl font-semibold text-black">{{ $user->first_name }} {{ $user->last_name }}</h2>
                            @if ($user->city || $user->country)
                                <p class="text-sm text-gray-400 mt-1">
                                    <i class="fa-solid fa-location-dot mr-1"></i>
                                    {{ $user->city }}{{ $user->city && $user->country ? ', ' : '' }}{{ $user->country }}
                                </p>
                            @endif
                            @if ($user->bio)
                                <p class="text-sm text-gray-600 mt-3 leading-relaxed">{{ $user->bio }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Report User --}}
                <div class="border border-gray-200 rounded-lg p-4 sm:p-6 bg-white">
                    <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">Report User</h2>
                    <form action="{{ route('user.report', $user->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="report-message" class="block text-sm font-medium text-gray-700 mb-1">Reason <span class="text-gray-400 font-normal">(10–500 characters)</span></label>
                            <textarea name="message" id="report-message" rows="3" placeholder="Please explain why you are reporting this user..." class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors resize-vertical"></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 text-sm font-medium rounded-md border border-gray-300 text-gray-600 hover:text-red-600 hover:border-red-300 transition-colors">
                                <i class="fa-solid fa-flag mr-1.5"></i> Submit Report
                            </button>
                        </div>
                    </form>

                    {{-- Report Errors --}}
                    @if ($errors->any())
                        <div class="mt-4 border border-gray-300 rounded-lg p-3 bg-gray-50">
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $err)
                                    <li class="text-sm text-gray-700">• {{$err}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- ═══════════════════════════════════
             USER'S POSTS (shown for both own and other profiles)
        ════════════════════════════════════ --}}
        <div class="mt-8">
            <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">
                {{ $user->id === auth()->user()->id ? 'My Posts' : $user->first_name . "'s Posts" }}
            </h2>
            <div class="space-y-4">
                @forelse ($posts as $post)
                    <a href="{{ route('post.show', $post->id) }}" class="block border border-gray-200 rounded-lg p-4 sm:p-5 bg-white hover:border-gray-400 transition-colors">
                        <h3 class="text-sm font-semibold text-black mb-1">{{ $post->title }}</h3>
                        @if ($post->address)
                            <p class="text-xs text-gray-400 mb-2">{{ $post->address }}</p>
                        @endif
                        <p class="text-sm text-gray-600 leading-relaxed mb-3">{{ Str::limit($post->content, 150) }}</p>

                        {{-- Post Images Thumbnails --}}
                        @if ($post->images && $post->images->count() > 0)
                            <div class="flex flex-wrap gap-2 mb-3">
                                @foreach ($post->images as $image)
                                    <img src="{{ asset($image->img_url) }}" class="w-14 h-14 rounded-md object-cover border border-gray-200" alt="Post image">
                                @endforeach
                            </div>
                        @endif

                        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                            <span class="text-xs text-gray-400">{{ $post->likes_count }} likes</span>
                            <span class="text-xs text-gray-400">{{ $post->comments_count }} comments</span>
                        </div>
                    </a>
                @empty
                    <div class="border border-gray-200 rounded-lg p-8 bg-white">
                        <p class="text-sm text-gray-400 text-center">No posts yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
@endsection

@section("script")
    <script>
        // Profile image preview on file select
        const profileInput = document.getElementById('profile_image');
        const profilePreview = document.getElementById('profile-image-preview');
        if (profileInput && profilePreview) {
            profileInput.addEventListener('change', () => {
                const file = profileInput.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => { profilePreview.src = e.target.result; };
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
@endsection