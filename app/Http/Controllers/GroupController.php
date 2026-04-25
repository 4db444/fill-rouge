<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseCreateRequest;
use App\Http\Requests\SettlementCreateRequest;
use App\Models\Expense;
use App\Models\ExpenseShares;
use App\Models\Group;
use App\Models\Settlement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    /**
     * List all groups the authenticated user belongs to.
     */
    public function index()
    {
        $user = Auth::user();
        
        $groups = $user->groups()
            ->withCount('members')
            ->with('admin')
            ->get()
            ->map(function ($group) {
                $group->total_expenses = $group->expenses()->sum('amount');
                return $group;
            });
            
        return view('groups.index', compact('groups'));
    }

    /**
     * Show group detail: members, balances, expenses, and settlements.
     */
    public function show(Group $group)
    {
        $user = Auth::user();

        // Ensure user is a member of this group
        if (!$group->members()->where('users.id', $user->id)->exists()) {
            abort(403);
        }

        // Load group relationships
        $group->load([
            'members.profile',
            'admin',
            'expenses' => function ($query) {
                $query->latest();
            },
            'expenses.user.profile',
            'expenses.expense_shares.user',
        ]);

        // Calculate balances between members
        $balances = $this->calculateBalances($group);

        // Get pending settlements where current user is the receiver (needs verification)
        $pendingSettlements = Settlement::where('group_id', $group->id)
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->with('sender.profile')
            ->latest()
            ->get();

        // Get all settlements for this group (for history display)
        $settledPayments = Settlement::where('group_id', $group->id)
            ->where('status', 'verified')
            ->with(['sender', 'receiver'])
            ->latest()
            ->get();

        // Get pending settlements sent by the current user
        $sentPendingSettlements = Settlement::where('group_id', $group->id)
            ->where('sender_id', $user->id)
            ->where('status', 'pending')
            ->with('receiver.profile')
            ->latest()
            ->get();

        return view('groups.show', compact(
            'group',
            'balances',
            'pendingSettlements',
            'settledPayments',
            'sentPendingSettlements'
        ));
    }

    /**
     * Leave a group. Blocked if the user has any outstanding debt (owes or is owed).
     */
    public function leaveGroup(Group $group)
    {
        $user = Auth::user();

        // Ensure user is a member
        if (!$group->members()->where('users.id', $user->id)->exists()) {
            abort(403);
        }

        // Admin cannot leave the group
        if ($group->user_id === $user->id) {
            return redirect()->back()->withErrors(['leave' => 'As the group admin, you cannot leave the group.']);
        }

        // Check for outstanding debts
        if ($this->memberHasDebt($group, $user->id)) {
            return redirect()->back()->withErrors(['leave' => 'You cannot leave this group because you have outstanding debts. Please settle all balances first.']);
        }

        // Detach the user from the group
        $group->members()->detach($user->id);

        return redirect()->route('groups.index')->with('success', 'You have left the group.');
    }

    /**
     * Remove a member from the group (admin only). Blocked if the member has outstanding debt.
     */
    public function removeMember(Group $group, User $member)
    {
        $user = Auth::user();

        // Only the admin can remove members
        if ($group->user_id !== $user->id) {
            abort(403);
        }

        // Admin cannot remove themselves
        if ($member->id === $user->id) {
            return redirect()->back()->withErrors(['remove' => 'You cannot remove yourself from the group.']);
        }

        // Ensure the member is actually in the group
        if (!$group->members()->where('users.id', $member->id)->exists()) {
            return redirect()->back()->withErrors(['remove' => 'This user is not a member of this group.']);
        }

        // Check for outstanding debts
        if ($this->memberHasDebt($group, $member->id)) {
            return redirect()->back()->withErrors(['remove' => 'You cannot remove this member because they have outstanding debts. All balances must be settled first.']);
        }

        // Detach the member from the group
        $group->members()->detach($member->id);

        return redirect()->back()->with('success', $member->first_name . ' has been removed from the group.');
    }

    /**
     * Create an expense and split it among the selected benefactors.
     */
    public function storeExpense(ExpenseCreateRequest $request, Group $group)
    {
        $user = Auth::user();

        // Ensure user is a member
        if (!$group->members()->where('users.id', $user->id)->exists()) {
            abort(403);
        }

        $validated = $request->validated();
        $benefactors = $validated['benefactors'];
        $shareAmount = round($validated['amount'] / count($benefactors), 2);

        DB::transaction(function () use ($group, $user, $validated, $benefactors, $shareAmount) {
            // Create the expense
            $expense = $group->expenses()->create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'amount' => $validated['amount'],
                'user_id' => $user->id,
            ]);

            // Create expense shares for each benefactor
            foreach ($benefactors as $benefactorId) {
                ExpenseShares::create([
                    'expense_id' => $expense->id,
                    'group_id' => $group->id,
                    'user_id' => $benefactorId,
                    'amount' => $shareAmount,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Expense added successfully.');
    }

    /**
     * Delete an expense (only the creator or group admin can delete).
     */
    public function deleteExpense(Group $group, Expense $expense)
    {
        $user = Auth::user();

        // Check that expense belongs to this group
        if ($expense->group_id !== $group->id) {
            abort(404);
        }

        // Only the expense creator or the group admin can delete
        if ($expense->user_id !== $user->id && $group->user_id !== $user->id) {
            abort(403);
        }

        $expense->delete();

        return redirect()->back()->with('success', 'Expense deleted.');
    }

    /**
     * Create a settlement payment (pending verification by receiver).
     */
    public function storeSettlement(SettlementCreateRequest $request, Group $group)
    {
        $user = Auth::user();

        // Ensure user is a member
        if (!$group->members()->where('users.id', $user->id)->exists()) {
            abort(403);
        }

        $validated = $request->validated();

        // Can't pay yourself
        if ($validated['receiver_id'] == $user->id) {
            return redirect()->back()->withErrors(['receiver_id' => "You can't pay yourself."]);
        }

        // Ensure receiver is also a member
        if (!$group->members()->where('users.id', $validated['receiver_id'])->exists()) {
            return redirect()->back()->withErrors(['receiver_id' => 'This user is not a group member.']);
        }

        Settlement::create([
            'sender_id' => $user->id,
            'receiver_id' => $validated['receiver_id'],
            'amount' => $validated['amount'],
            'group_id' => $group->id,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Payment sent. Waiting for receiver to verify.');
    }

    /**
     * Receiver verifies that they received the payment.
     */
    public function verifySettlement(Group $group, Settlement $settlement)
    {
        $user = Auth::user();

        // Only the receiver can verify
        if ($settlement->receiver_id !== $user->id || $settlement->group_id !== $group->id) {
            abort(403);
        }

        $settlement->update(['status' => 'verified']);

        return redirect()->back()->with('success', 'Payment verified.');
    }

    /**
     * Receiver rejects the settlement (deletes it).
     */
    public function rejectSettlement(Group $group, Settlement $settlement)
    {
        $user = Auth::user();

        // Only the receiver can reject
        if ($settlement->receiver_id !== $user->id || $settlement->group_id !== $group->id) {
            abort(403);
        }

        $settlement->delete();

        return redirect()->back()->with('success', 'Payment rejected.');
    }

    /**
     * Check if a member has any outstanding debt (owes or is owed) in a group.
     * Returns true if the member appears in any balance entry.
     */
    private function memberHasDebt(Group $group, int $memberId): bool
    {
        $group->load(['members.profile', 'admin']);
        $balances = $this->calculateBalances($group);

        foreach ($balances as $balance) {
            if ($balance['from']->id === $memberId || $balance['to']->id === $memberId) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate net balances between all members of a group.
     *
     * Returns an array of: ['from' => User, 'to' => User, 'amount' => float]
     * representing who owes whom after accounting for expenses and verified settlements.
     */
    private function calculateBalances(Group $group)
    {
        $members = $group->members;
        $memberIds = $members->pluck('id')->toArray();

        // Build a net balance matrix: net[A][B] = how much A owes B
        $net = [];
        foreach ($memberIds as $a) {
            foreach ($memberIds as $b) {
                $net[$a][$b] = 0;
            }
        }

        // Process expenses: for each expense share, the benefactor owes the payer
        $expenses = $group->expenses()->with('expense_shares')->get();
        foreach ($expenses as $expense) {
            $payerId = $expense->user_id;
            foreach ($expense->expense_shares as $share) {
                $benefactorId = $share->user_id;
                if ($benefactorId !== $payerId) {
                    // benefactor owes payer the share amount
                    $net[$benefactorId][$payerId] += $share->amount;
                }
            }
        }

        // Process verified settlements: sender paid receiver, reduce what sender owes receiver
        $settlements = Settlement::where('group_id', $group->id)
            ->where('status', 'verified')
            ->get();
        foreach ($settlements as $settlement) {
            $net[$settlement->sender_id][$settlement->receiver_id] -= $settlement->amount;
        }

        // Simplify: compute net between each pair
        $balances = [];
        $processed = [];
        foreach ($memberIds as $a) {
            foreach ($memberIds as $b) {
                if ($a >= $b) continue;
                $key = min($a, $b) . '-' . max($a, $b);
                if (isset($processed[$key])) continue;
                $processed[$key] = true;

                $netAmount = $net[$a][$b] - $net[$b][$a];
                if (abs($netAmount) < 0.01) continue;

                if ($netAmount > 0) {
                    // A owes B
                    $balances[] = [
                        'from' => $members->firstWhere('id', $a),
                        'to' => $members->firstWhere('id', $b),
                        'amount' => round($netAmount, 2),
                    ];
                } else {
                    // B owes A
                    $balances[] = [
                        'from' => $members->firstWhere('id', $b),
                        'to' => $members->firstWhere('id', $a),
                        'amount' => round(abs($netAmount), 2),
                    ];
                }
            }
        }

        return $balances;
    }
}
