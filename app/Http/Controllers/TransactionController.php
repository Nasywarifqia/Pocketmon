<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Income;
use App\Models\Expense;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $incomes = Income::where('user_id', $userId)
            ->with('wallet')
            ->get()
            ->map(function($item) {
                return [
                    'id'          => $item->id,
                    'date'        => $item->date,
                    'type'        => 'income',
                    'category'    => $item->category,
                    'description' => $item->description,
                    'amount'      => $item->amount,
                    'wallet'      => $item->wallet->name ?? '-',
                ];
            });

        $expenses = Expense::where('user_id', $userId)
            ->with('wallet')
            ->get()
            ->map(function($item) {
                return [
                    'id'          => $item->id,
                    'date'        => $item->date,
                    'type'        => 'expense',
                    'category'    => $item->category,
                    'description' => $item->description,
                    'amount'      => $item->amount,
                    'wallet'      => $item->wallet->name ?? '-',
                ];
            });

        $transactions = $incomes->concat($expenses)
            ->sortByDesc('date')
            ->values();

        // Filter
        if ($request->type && $request->type != 'all') {
            $transactions = $transactions->filter(fn($t) => $t['type'] === $request->type)->values();
        }

        if ($request->search) {
            $transactions = $transactions->filter(function($t) use ($request) {
                return str_contains(strtolower($t['category']), strtolower($request->search))
                    || str_contains(strtolower($t['description'] ?? ''), strtolower($request->search));
            })->values();
        }

        if ($request->date_from) {
            $transactions = $transactions->filter(fn($t) => $t['date'] >= $request->date_from)->values();
        }

        if ($request->date_to) {
            $transactions = $transactions->filter(fn($t) => $t['date'] <= $request->date_to)->values();
        }

        // Pagination manual
        $perPage = 15;
        $page = $request->page ?? 1;
        $total = $transactions->count();
        $transactions = $transactions->forPage($page, $perPage);

        return view('transactions.index', compact('transactions', 'total', 'perPage', 'page'));
    }
}