<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $incomes = Income::where('user_id', $userId)->with('wallet')
            ->get()->map(function($item) {
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

        $expenses = Expense::where('user_id', $userId)->with('wallet')
            ->get()->map(function($item) {
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

        $transactions = $incomes->concat($expenses)->sortByDesc('date')->values();

        if ($request->type && $request->type != 'all') {
            $transactions = $transactions->filter(fn($t) => $t['type'] === $request->type)->values();
        }

        if ($request->date_from) {
            $transactions = $transactions->filter(fn($t) => $t['date'] >= $request->date_from)->values();
        }

        if ($request->date_to) {
            $transactions = $transactions->filter(fn($t) => $t['date'] <= $request->date_to)->values();
        }

        return response()->json([
            'success' => true,
            'data'    => $transactions
        ]);
    }
}