<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Wallet;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Income::where('user_id', $request->user()->id)->with('wallet');

        if ($request->category) {
            $query->where('category', $request->category);
        }
        if ($request->date_from) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->where('date', '<=', $request->date_to);
        }

        $incomes = $query->orderBy('date', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $incomes
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

        $income = Income::create([
            'user_id'     => $request->user()->id,
            'wallet_id'   => $request->wallet_id,
            'date'        => $request->date,
            'amount'      => $request->amount,
            'category'    => $request->category,
            'description' => $request->description,
        ]);

        $wallet = Wallet::findOrFail($request->wallet_id);
        $wallet->increment('balance', $request->amount);

        return response()->json([
            'success' => true,
            'message' => 'Pemasukan berhasil ditambahkan!',
            'data'    => $income
        ], 201);
    }

    public function update(Request $request, Income $income)
    {
        abort_if($income->user_id !== $request->user()->id, 403);

        $request->validate([
            'date'        => 'required|date',
            'amount'      => 'required|numeric|min:1',
            'category'    => 'required|string',
            'wallet_id'   => 'required|exists:wallets,id',
            'description' => 'nullable|string',
        ]);

        if ($income->wallet_id) {
            $oldWallet = Wallet::find($income->wallet_id);
            if ($oldWallet) $oldWallet->decrement('balance', $income->amount);
        }

        $income->update($request->only('date', 'amount', 'category', 'wallet_id', 'description'));

        $newWallet = Wallet::findOrFail($request->wallet_id);
        $newWallet->increment('balance', $request->amount);

        return response()->json([
            'success' => true,
            'message' => 'Pemasukan berhasil diupdate!',
            'data'    => $income
        ]);
    }

    public function destroy(Request $request, Income $income)
    {
        abort_if($income->user_id !== $request->user()->id, 403);

        if ($income->wallet_id) {
            $wallet = Wallet::find($income->wallet_id);
            if ($wallet) $wallet->decrement('balance', $income->amount);
        }

        $income->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pemasukan berhasil dihapus!'
        ]);
    }
}