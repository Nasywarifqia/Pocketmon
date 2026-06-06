<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Income;
use App\Models\Wallet;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Income::where('user_id', Auth::id())->with('wallet');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('category', 'like', '%'.$request->search.'%')
                  ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->date_from) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->where('date', '<=', $request->date_to);
        }

        $incomes = $query->orderBy('date', 'desc')->paginate(10);
        $categories = ['Gaji', 'Bonus', 'Bisnis', 'Freelance', 'Hadiah', 'Lainnya'];
        $wallets = Wallet::where('user_id', Auth::id())->get();

        return view('incomes.index', compact('incomes', 'categories', 'wallets'));
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

        Income::create([
            'user_id'     => Auth::id(),
            'wallet_id'   => $request->wallet_id,
            'date'        => $request->date,
            'amount'      => $request->amount,
            'category'    => $request->category,
            'description' => $request->description,
        ]);

        // Tambah saldo wallet
        $wallet = Wallet::findOrFail($request->wallet_id);
        $wallet->increment('balance', $request->amount);

        return redirect()->route('incomes.index')->with('success', 'Pemasukan berhasil ditambahkan!');
    }

    public function update(Request $request, Income $income)
    {
        abort_if($income->user_id !== Auth::id(), 403);

        $request->validate([
            'date'        => 'required|date',
            'amount'      => 'required|numeric|min:1',
            'category'    => 'required|string',
            'wallet_id'   => 'required|exists:wallets,id',
            'description' => 'nullable|string',
        ]);

        // Kembalikan saldo wallet lama
        if ($income->wallet_id) {
            $oldWallet = Wallet::find($income->wallet_id);
            if ($oldWallet) $oldWallet->decrement('balance', $income->amount);
        }

        $income->update($request->only('date', 'amount', 'category', 'wallet_id', 'description'));

        // Tambah saldo wallet baru
        $newWallet = Wallet::findOrFail($request->wallet_id);
        $newWallet->increment('balance', $request->amount);

        return redirect()->route('incomes.index')->with('success', 'Pemasukan berhasil diupdate!');
    }

    public function destroy(Income $income)
    {
        abort_if($income->user_id !== Auth::id(), 403);

        // Kurangi saldo wallet
        if ($income->wallet_id) {
            $wallet = Wallet::find($income->wallet_id);
            if ($wallet) $wallet->decrement('balance', $income->amount);
        }

        $income->delete();
        return redirect()->route('incomes.index')->with('success', 'Pemasukan berhasil dihapus!');
    }
}