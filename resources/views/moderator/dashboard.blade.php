@extends("layout.base")

@section("main")
<main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
    <h1 class="text-2xl font-semibold text-black mb-8">Moderator Dashboard</h1>

    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Recent User Reports</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($reports as $report)
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-sm text-gray-600">
                            <a href="{{ route('user.profile', $report->reporter_id) }}" class="font-semibold text-black hover:underline">{{ $report->reporter->first_name }} {{ $report->reporter->last_name }}</a>
                            reported
                            <a href="{{ route('user.profile', $report->reported_id) }}" class="font-semibold text-black hover:underline">{{ $report->reported->first_name }} {{ $report->reported->last_name }}</a>
                        </div>
                        <span class="text-xs text-gray-400">{{ $report->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-md text-sm text-gray-800 border border-gray-100 mb-4">
                        "{{ $report->message }}"
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('moderator.user.reports', $report->reported_id) }}" class="text-xs font-medium text-gray-600 hover:text-black transition-colors underline">View all reports for this user</a>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-sm text-gray-500">
                    No reports found.
                </div>
            @endforelse
        </div>
        @if ($reports->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</main>
@endsection
