@extends("layout.base")

@section("main")
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <h1 class="text-2xl font-semibold text-black mb-6">My Groups</h1>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-6 border border-gray-200 rounded-lg p-4 bg-gray-50">
                <p class="text-sm text-gray-700">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Groups Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse ($groups as $group)
                <a href="{{ route('groups.show', $group->id) }}" class="block border border-gray-200 rounded-lg p-5 bg-white hover:border-gray-400 transition-colors group">
                    <div class="flex items-start justify-between mb-3">
                        <div class="min-w-0 flex-1">
                            <h2 class="text-base font-semibold text-black truncate group-hover:underline">{{ $group->name }}</h2>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Created by {{ $group->admin->first_name }} {{ $group->admin->last_name }}
                            </p>
                        </div>
                        @if ($group->user_id === auth()->user()->id)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-black text-white shrink-0 ml-2">Admin</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-4 pt-3 border-t border-gray-100">
                        <span class="text-sm text-gray-500">
                            <i class="fa-regular fa-user mr-1"></i>{{ $group->members_count }} {{ $group->members_count === 1 ? 'member' : 'members' }}
                        </span>
                        <span class="text-sm text-gray-500">
                            <i class="fa-regular fa-credit-card mr-1"></i>{{ number_format($group->total_expenses, 2) }} DH
                        </span>
                    </div>
                </a>
            @empty
                <div class="col-span-full border border-gray-200 rounded-lg p-12 bg-white">
                    <p class="text-sm text-gray-400 text-center">You don't belong to any groups yet.</p>
                    <p class="text-xs text-gray-300 text-center mt-1">Groups are created when requests are accepted on posts.</p>
                </div>
            @endforelse
        </div>
    </main>
@endsection
