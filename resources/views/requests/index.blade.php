@extends("layout.base")

@section("main")
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <h1 class="text-2xl font-semibold text-black mb-6">Requests</h1>

        {{-- Tabs --}}
        <div class="mb-6">
            <div class="flex border-b border-gray-200">
                <button id="tab-sent" class="tab-btn px-4 py-2.5 text-sm font-medium border-b-2 border-black text-black transition-colors">
                    Sent
                    @if ($sentRequests->count() > 0)
                        <span class="ml-1.5 inline-flex items-center justify-center w-5 h-5 text-xs font-medium bg-black text-white rounded-full">{{ $sentRequests->count() }}</span>
                    @endif
                </button>
                <button id="tab-received" class="tab-btn px-4 py-2.5 text-sm font-medium border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition-colors">
                    Received
                    @if ($receivedRequests->sum(fn($post) => $post->requests->count()) > 0)
                        <span class="ml-1.5 inline-flex items-center justify-center w-5 h-5 text-xs font-medium bg-gray-400 text-white rounded-full">{{ $receivedRequests->sum(fn($post) => $post->requests->count()) }}</span>
                    @endif
                </button>
            </div>
        </div>

        {{-- Sent Requests Panel --}}
        <div id="panel-sent" class="tab-panel">
            <div class="space-y-3">
                @forelse ($sentRequests as $post)
                    <div class="border border-gray-200 rounded-lg p-4 sm:p-5 bg-white flex items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <a href="{{ route('post.show', $post->id) }}" class="text-sm font-semibold text-black hover:underline truncate block">{{ $post->title }}</a>
                            @if ($post->address)
                                <p class="text-xs text-gray-400 mt-0.5">{{ $post->address }}</p>
                            @endif
                            <div class="flex items-center gap-3 mt-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 mr-1.5"></span>
                                    Pending
                                </span>
                                <span class="text-xs text-gray-400">{{ $post->pivot->created_at ? \Carbon\Carbon::parse($post->pivot->created_at)->diffForHumans() : '' }}</span>
                            </div>
                        </div>
                        <form action="{{ route('requests.cancel', $post->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="shrink-0 text-sm font-medium px-4 py-2 rounded-md border border-gray-300 text-gray-600 hover:text-black hover:border-black transition-colors">
                                Cancel
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="border border-gray-200 rounded-lg p-8 bg-white">
                        <p class="text-sm text-gray-400 text-center">You haven't sent any requests yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Received Requests Panel --}}
        <div id="panel-received" class="tab-panel hidden">
            <div class="space-y-3">
                @forelse ($receivedRequests as $post)
                    @foreach ($post->requests as $requester)
                        <div class="border border-gray-200 rounded-lg p-4 sm:p-5 bg-white">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <a href="{{ route('user.profile', $requester->id) }}">
                                        <img 
                                            src="{{ asset($requester->profile->img_url ?? 'images/default-avatar.png') }}" 
                                            class="w-9 h-9 rounded-full object-cover ring-2 ring-gray-200 hover:ring-gray-400 transition-all shrink-0"
                                            alt="{{ $requester->first_name }}">
                                    </a>
                                    <div class="min-w-0">
                                        <a href="{{ route('user.profile', $requester->id) }}" class="text-sm font-semibold text-black hover:underline">{{ $requester->first_name }} {{ $requester->last_name }}</a>
                                        <p class="text-xs text-gray-400 mt-0.5 truncate">
                                            wants to join <span class="font-medium text-gray-600">{{ $post->title }}</span>
                                        </p>
                                        <span class="text-xs text-gray-400">{{ $requester->pivot->created_at ? \Carbon\Carbon::parse($requester->pivot->created_at)->diffForHumans() : '' }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <form action="{{ route('requests.accept', [$post->id, $requester->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-sm font-medium px-4 py-2 rounded-md bg-black text-white hover:bg-gray-800 transition-colors">
                                            Accept
                                        </button>
                                    </form>
                                    <form action="{{ route('requests.reject', [$post->id, $requester->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-medium px-4 py-2 rounded-md border border-gray-300 text-gray-600 hover:text-red-500 hover:border-red-300 transition-colors">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @empty
                    <div class="border border-gray-200 rounded-lg p-8 bg-white">
                        <p class="text-sm text-gray-400 text-center">No requests received yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
@endsection

@section("script")
    @vite(["resources/js/request-tabs.js"])
@endsection
