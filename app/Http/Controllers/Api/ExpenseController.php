<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Wallet;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::where('user_id', $request->user()->id)->with('wallet');

        if ($request->category) {
            $query->where('category', $request->category);
        }
        if ($request->date_from) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->where('date', '<=', $request->date_to);
        }

        $expenses = $query->orderBy('date', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $expenses
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'        => 'required|date',
            'amount'      => 'required|numeric|min:1',
            'category'    => 'required|string',
            'wallet_id'   => 'required|exists:wallets,id',
            'description' => 'nullable|string',
        ]);

        $wallet = Wallet::findOrFail($request->wallet_id);

        if ($wallet->balance < $request->amount) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo wallet tidak mencukupi!'
            ], 400);
        }

        $expense = Expense::create([
            'user_id'     => $request->user()->id,
            'wallet_id'   => $request->wallet_id,
            'date'        => $request->date,
            'amount'      => $request->amount,
            'category'    => $request->category,
            'description' => $request->description,
        ]);

        $wallet->decrement('balance', $request->amount);

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil ditambahkan!',
            'data'    => $expense
        ], 201);
    }

    public function update(Request $request, Expense $expense)
    {
        abort_if($expense->user_id !== $request->user()->id, 403);

        $request->validate([
            'date'        => 'required|date',
            'amount'      => 'required|numeric|min:1',
            'category'    => 'required|string',
            'wallet_id'   => 'required|exists:wallets,id',
            'description' => 'nullable|string',
        ]);

        if ($expense->wallet_id) {
            $oldWallet = Wallet::find($expense->wallet_id);
            if ($oldWallet) $oldWallet->increment('balance', $expense->amount);
        }

        $expense->update($request->only('date', 'amount', 'category', 'wallet_id', 'description'));

        $newWallet = Wallet::findOrFail($request->wallet_id);
        $newWallet->decrement('balance', $request->amount);

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil diupdate!',
            'data'    => $expense
        ]);
    }

    public function destroy(Request $request, Expense $expense)
    {
        abort_if($expense->user_id !== $request->user()->id, 403);

        if ($expense->wallet_id) {
            $wallet = Wallet::find($expense->wallet_id);
            if ($wallet) $wallet->increment('balance', $expense->amount);
        }

        $expense->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil dihapus!'
        ]);
    }
}