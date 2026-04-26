@extends("layout.base")

@section("main")
<main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-semibold text-black">Admin Dashboard</h1>
        <a href="{{ route('moderator.dashboard') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition-colors">Go to Moderator Dashboard</a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="p-6 bg-white border border-gray-200 rounded-lg">
            <h3 class="text-sm font-medium text-gray-500 mb-1">Total Users</h3>
            <p class="text-3xl font-semibold text-black">{{ $totalUsers }}</p>
        </div>
        <div class="p-6 bg-white border border-gray-200 rounded-lg">
            <h3 class="text-sm font-medium text-gray-500 mb-1">Total Groups</h3>
            <p class="text-3xl font-semibold text-black">{{ $totalGroups }}</p>
        </div>
        <div class="p-6 bg-white border border-gray-200 rounded-lg">
            <h3 class="text-sm font-medium text-gray-500 mb-1">Total Posts</h3>
            <p class="text-3xl font-semibold text-black">{{ $totalPosts }}</p>
        </div>
    </div>

    {{-- Users List --}}
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Users Directory</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($users as $user)
                <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset($user->profile->img_url ?? 'storage/images/profiles/default.png') }}" alt="{{ $user->first_name }}" class="w-12 h-12 rounded-full object-cover bg-gray-100">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</h3>
                            <p class="text-xs text-gray-500">{{ $user->email }} &middot; Role: <span class="font-medium text-black capitalize">{{ $user->role }}</span></p>
                        </div>
                    </div>
                    @if ($user->id !== auth()->id())
                        <div class="flex gap-2">
                            @if ($user->role === 'user')
                                <form action="{{ route('admin.users.role', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="role" value="moderator">
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-black text-white rounded-md hover:bg-gray-800 transition-colors">Make Moderator</button>
                                </form>
                            @elseif ($user->role === 'moderator')
                                <form action="{{ route('admin.users.role', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="role" value="user">
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">Remove Moderator</button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        @if ($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</main>
@endsection
