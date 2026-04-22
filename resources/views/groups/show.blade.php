@extends("layout.base")

@section("main")
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">

        {{-- Group Header --}}
        <div class="mb-6">
            <a href="{{ route('groups.index') }}" class="text-sm text-gray-400 hover:text-black transition-colors mb-2 inline-block">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back to groups
            </a>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-black">{{ $group->name }}</h1>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $group->members->count() }} members · Admin: {{ $group->admin->first_name }} {{ $group->admin->last_name }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Success / Error Messages --}}
        @if (session('success'))
            <div class="mb-6 border border-gray-200 rounded-lg p-4 bg-gray-50">
                <p class="text-sm text-gray-700">{{ session('success') }}</p>
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-6 border border-gray-300 rounded-lg p-4 bg-gray-50">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $err)
                        <li class="text-sm text-gray-700">• {{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Members Row --}}
        <div class="border border-gray-200 rounded-lg p-4 bg-white mb-6">
            <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Members</h2>
            <div class="flex flex-wrap gap-3">
                @foreach ($group->members as $member)
                    <a href="{{ route('user.profile', $member->id) }}" class="flex items-center gap-2 px-3 py-1.5 rounded-full border border-gray-200 hover:border-gray-400 transition-colors group">
                        <img 
                            src="{{ asset($member->profile->img_url ?? 'images/default-avatar.png') }}" 
                            class="w-6 h-6 rounded-full object-cover"
                            alt="{{ $member->first_name }}">
                        <span class="text-sm text-gray-700 group-hover:text-black transition-colors">{{ $member->first_name }} {{ $member->last_name }}</span>
                        @if ($member->id === $group->user_id)
                            <span class="text-xs text-gray-400">·&nbsp;admin</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Tabs --}}
        <div class="mb-6">
            <div class="flex border-b border-gray-200">
                <button id="tab-balances" class="group-tab px-4 py-2.5 text-sm font-medium border-b-2 border-black text-black transition-colors">
                    Balances
                </button>
                <button id="tab-expenses" class="group-tab px-4 py-2.5 text-sm font-medium border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition-colors">
                    Expenses
                    @if ($group->expenses->count() > 0)
                        <span class="ml-1.5 inline-flex items-center justify-center w-5 h-5 text-xs font-medium bg-gray-400 text-white rounded-full">{{ $group->expenses->count() }}</span>
                    @endif
                </button>
                <button id="tab-settlements" class="group-tab px-4 py-2.5 text-sm font-medium border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition-colors">
                    Settlements
                    @if ($pendingSettlements->count() > 0)
                        <span class="ml-1.5 inline-flex items-center justify-center w-5 h-5 text-xs font-medium bg-gray-400 text-white rounded-full">{{ $pendingSettlements->count() }}</span>
                    @endif
                </button>
            </div>
        </div>

        {{-- ═══════════════════════════════════
             BALANCES TAB
        ════════════════════════════════════ --}}
        <div id="panel-balances" class="group-panel">
            <div class="border border-gray-200 rounded-lg p-4 sm:p-6 bg-white">
                <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">Who Owes Whom</h2>
                <div class="space-y-3">
                    @forelse ($balances as $balance)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <img 
                                    src="{{ asset($balance['from']->profile->img_url ?? 'images/default-avatar.png') }}" 
                                    class="w-7 h-7 rounded-full object-cover shrink-0"
                                    alt="{{ $balance['from']->first_name }}">
                                <span class="text-sm font-medium text-gray-800 truncate">{{ $balance['from']->first_name }}</span>
                                <i class="fa-solid fa-arrow-right text-xs text-gray-300 shrink-0"></i>
                                <img 
                                    src="{{ asset($balance['to']->profile->img_url ?? 'images/default-avatar.png') }}" 
                                    class="w-7 h-7 rounded-full object-cover shrink-0"
                                    alt="{{ $balance['to']->first_name }}">
                                <span class="text-sm font-medium text-gray-800 truncate">{{ $balance['to']->first_name }}</span>
                            </div>
                            <span class="text-sm font-semibold text-black shrink-0 ml-3">{{ number_format($balance['amount'], 2) }} DH</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-6">All settled up! No outstanding balances.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════
             EXPENSES TAB
        ════════════════════════════════════ --}}
        <div id="panel-expenses" class="group-panel hidden">

            {{-- Add Expense Form --}}
            <div class="border border-gray-200 rounded-lg p-4 sm:p-6 bg-white mb-4">
                <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">Add Expense</h2>
                <form action="{{ route('groups.expenses.store', $group->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="expense-title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" id="expense-title" name="title" placeholder="e.g. Dinner, Uber ride" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors" required>
                        </div>
                        <div>
                            <label for="expense-amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (DH)</label>
                            <input type="number" step="0.01" min="0.01" id="expense-amount" name="amount" placeholder="0.00" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors" required>
                        </div>
                    </div>
                    <div>
                        <label for="expense-description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-gray-400 font-normal">(optional)</span></label>
                        <input type="text" id="expense-description" name="description" placeholder="Brief note..." class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Who benefited from this expense?</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($group->members as $member)
                                <label class="flex items-center gap-2 px-3 py-2 rounded-md border border-gray-200 cursor-pointer has-[:checked]:border-black has-[:checked]:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="benefactors[]" value="{{ $member->id }}" class="accent-black w-3.5 h-3.5">
                                    <img 
                                        src="{{ asset($member->profile->img_url ?? 'images/default-avatar.png') }}" 
                                        class="w-5 h-5 rounded-full object-cover"
                                        alt="{{ $member->first_name }}">
                                    <span class="text-sm text-gray-700">{{ $member->first_name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-black text-white text-sm font-medium px-6 py-2 rounded-md hover:bg-gray-800 transition-colors">Add Expense</button>
                    </div>
                </form>
            </div>

            {{-- Expenses List --}}
            <div class="space-y-3">
                @forelse ($group->expenses as $expense)
                    <div class="border border-gray-200 rounded-lg p-4 sm:p-5 bg-white">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="text-sm font-semibold text-black truncate">{{ $expense->title }}</h3>
                                    <span class="text-sm font-semibold text-black shrink-0">{{ number_format($expense->amount, 2) }} DH</span>
                                </div>
                                @if ($expense->description)
                                    <p class="text-xs text-gray-400 mb-2">{{ $expense->description }}</p>
                                @endif
                                <div class="flex items-center gap-2 mb-2">
                                    <img 
                                        src="{{ asset($expense->user->profile->img_url ?? 'images/default-avatar.png') }}" 
                                        class="w-5 h-5 rounded-full object-cover"
                                        alt="{{ $expense->user->first_name }}">
                                    <span class="text-xs text-gray-500">Paid by <span class="font-medium text-gray-700">{{ $expense->user->first_name }} {{ $expense->user->last_name }}</span></span>
                                    <span class="text-xs text-gray-300">·</span>
                                    <span class="text-xs text-gray-400">{{ $expense->created_at->diffForHumans() }}</span>
                                </div>
                                {{-- Benefactors --}}
                                <div class="flex items-center gap-1 flex-wrap">
                                    <span class="text-xs text-gray-400 mr-1">Split between:</span>
                                    @foreach ($expense->expense_shares as $share)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-gray-100 text-xs text-gray-600">
                                            <img 
                                                src="{{ asset($share->user->profile->img_url ?? 'images/default-avatar.png') }}" 
                                                class="w-4 h-4 rounded-full object-cover"
                                                alt="{{ $share->user->first_name }}">
                                            {{ $share->user->first_name }}
                                            <span class="text-gray-400">({{ number_format($share->amount, 2) }})</span>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            {{-- Delete button: only for creator or group admin --}}
                            @if ($expense->user_id === auth()->user()->id || $group->user_id === auth()->user()->id)
                                <form action="{{ route('groups.expenses.delete', [$group->id, $expense->id]) }}" method="POST" class="shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-gray-400 hover:text-red-500 transition-colors p-1" onclick="return confirm('Delete this expense?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="border border-gray-200 rounded-lg p-8 bg-white">
                        <p class="text-sm text-gray-400 text-center">No expenses yet. Add one above!</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ═══════════════════════════════════
             SETTLEMENTS TAB
        ════════════════════════════════════ --}}
        <div id="panel-settlements" class="group-panel hidden">

            {{-- Pay Someone Form --}}
            <div class="border border-gray-200 rounded-lg p-4 sm:p-6 bg-white mb-4">
                <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">Pay Someone</h2>
                <form action="{{ route('groups.settlements.store', $group->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="settlement-receiver" class="block text-sm font-medium text-gray-700 mb-1">Pay to</label>
                            <select id="settlement-receiver" name="receiver_id" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors bg-white" required>
                                <option value="">Select member...</option>
                                @foreach ($group->members as $member)
                                    @if ($member->id !== auth()->user()->id)
                                        <option value="{{ $member->id }}">{{ $member->first_name }} {{ $member->last_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="settlement-amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (DH)</label>
                            <input type="number" step="0.01" min="0.01" id="settlement-amount" name="amount" placeholder="0.00" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:border-black transition-colors" required>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-black text-white text-sm font-medium px-6 py-2 rounded-md hover:bg-gray-800 transition-colors">Send Payment</button>
                    </div>
                </form>
            </div>

            {{-- Pending Verifications (I need to verify) --}}
            @if ($pendingSettlements->count() > 0)
                <div class="border border-gray-200 rounded-lg p-4 sm:p-6 bg-white mb-4">
                    <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">
                        Pending Verifications
                        <span class="ml-1.5 inline-flex items-center justify-center w-5 h-5 text-xs font-medium bg-black text-white rounded-full">{{ $pendingSettlements->count() }}</span>
                    </h2>
                    <div class="space-y-3">
                        @foreach ($pendingSettlements as $settlement)
                            <div class="flex items-center justify-between gap-3 py-3 border-b border-gray-100 last:border-0">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <img 
                                        src="{{ asset($settlement->sender->profile->img_url ?? 'images/default-avatar.png') }}" 
                                        class="w-8 h-8 rounded-full object-cover shrink-0"
                                        alt="{{ $settlement->sender->first_name }}">
                                    <div class="min-w-0">
                                        <p class="text-sm text-gray-700">
                                            <span class="font-semibold text-black">{{ $settlement->sender->first_name }}</span>
                                            paid you
                                            <span class="font-semibold text-black">{{ number_format($settlement->amount, 2) }} DH</span>
                                        </p>
                                        <p class="text-xs text-gray-400">{{ $settlement->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <form action="{{ route('groups.settlements.verify', [$group->id, $settlement->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-sm font-medium px-3 py-1.5 rounded-md bg-black text-white hover:bg-gray-800 transition-colors">Verify</button>
                                    </form>
                                    <form action="{{ route('groups.settlements.reject', [$group->id, $settlement->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-medium px-3 py-1.5 rounded-md border border-gray-300 text-gray-600 hover:text-red-500 hover:border-red-300 transition-colors">Reject</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Pending Payments I Sent --}}
            @if ($sentPendingSettlements->count() > 0)
                <div class="border border-gray-200 rounded-lg p-4 sm:p-6 bg-white mb-4">
                    <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">Awaiting Verification</h2>
                    <div class="space-y-3">
                        @foreach ($sentPendingSettlements as $settlement)
                            <div class="flex items-center justify-between gap-3 py-3 border-b border-gray-100 last:border-0">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <img 
                                        src="{{ asset($settlement->receiver->profile->img_url ?? 'images/default-avatar.png') }}" 
                                        class="w-8 h-8 rounded-full object-cover shrink-0"
                                        alt="{{ $settlement->receiver->first_name }}">
                                    <div class="min-w-0">
                                        <p class="text-sm text-gray-700">
                                            You paid
                                            <span class="font-semibold text-black">{{ $settlement->receiver->first_name }}</span>
                                            <span class="font-semibold text-black">{{ number_format($settlement->amount, 2) }} DH</span>
                                        </p>
                                        <p class="text-xs text-gray-400">{{ $settlement->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 mr-1.5"></span>
                                    Pending
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Settlement History --}}
            <div class="border border-gray-200 rounded-lg p-4 sm:p-6 bg-white">
                <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">Payment History</h2>
                <div class="space-y-3">
                    @forelse ($settledPayments as $settlement)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <img 
                                    src="{{ asset($settlement->sender->profile->img_url ?? 'images/default-avatar.png') }}" 
                                    class="w-6 h-6 rounded-full object-cover shrink-0"
                                    alt="{{ $settlement->sender->first_name }}">
                                <span class="text-sm text-gray-700 truncate">{{ $settlement->sender->first_name }}</span>
                                <i class="fa-solid fa-arrow-right text-xs text-gray-300 shrink-0"></i>
                                <img 
                                    src="{{ asset($settlement->receiver->profile->img_url ?? 'images/default-avatar.png') }}" 
                                    class="w-6 h-6 rounded-full object-cover shrink-0"
                                    alt="{{ $settlement->receiver->first_name }}">
                                <span class="text-sm text-gray-700 truncate">{{ $settlement->receiver->first_name }}</span>
                            </div>
                            <div class="flex items-center gap-2 shrink-0 ml-3">
                                <span class="text-sm font-semibold text-black">{{ number_format($settlement->amount, 2) }} DH</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 mr-1.5"></span>
                                    Verified
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-4">No payments have been verified yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </main>
@endsection

@section("script")
    @vite(["resources/js/group-tabs.js"])
@endsection
