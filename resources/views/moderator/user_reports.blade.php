@extends("layout.base")

@section("main")
<main class="max-w-3xl mx-auto px-4 sm:px-6 py-8">
    <div class="flex items-center gap-3 mb-8 text-sm">
        <a href="{{ route('moderator.dashboard') }}" class="text-gray-500 hover:text-black transition-colors">Reports</a>
        <span class="text-gray-300">/</span>
        <span class="text-black font-medium">{{ $user->first_name }} {{ $user->last_name }}</span>
    </div>

    <div class="p-6 bg-white border border-gray-200 rounded-lg flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <img src="{{ asset($user->profile->img_url ?? 'storage/images/profiles/default.png') }}" class="w-16 h-16 rounded-full object-cover bg-gray-100">
            <div>
                <h1 class="text-xl font-semibold text-black">{{ $user->first_name }} {{ $user->last_name }}</h1>
                <p class="text-sm text-gray-500">{{ $reports->total() }} reports total</p>
            </div>
        </div>
        @if (!in_array($user->role, ['admin', 'moderator']) && $user->id !== auth()->id())
            <form action="{{ route('moderator.users.ban', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $user->is_banned ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-red-600 text-white hover:bg-red-700' }}">
                    {{ $user->is_banned ? 'Unban User' : 'Ban User' }}
                </button>
            </form>
        @endif
    </div>

    <div class="space-y-4">
        @forelse($reports as $report)
            <div class="p-4 sm:p-6 bg-white border border-gray-200 rounded-lg">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-black">{{ $report->reporter->first_name }} {{ $report->reporter->last_name }}</span>
                    <span class="text-xs text-gray-400">{{ $report->created_at->format('M d, Y') }}</span>
                </div>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $report->message }}</p>
            </div>
        @empty
            <p class="text-sm text-gray-500 text-center py-8 border border-gray-200 rounded-lg bg-gray-50">No reports found for this user.</p>
        @endforelse
    </div>
    
    @if ($reports->hasPages())
        <div class="mt-6">
            {{ $reports->links() }}
        </div>
    @endif
</main>
@endsection
