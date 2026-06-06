<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Expense;
use App\Models\Wallet;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::where('user_id', Auth::id())->with('wallet');

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

        $expenses = $query->orderBy('date', 'desc')->paginate(10);
        $categories = ['Makanan', 'Transportasi', 'Pendidikan', 'Belanja', 'Hiburan', 'Kesehatan', 'Tagihan', 'Lainnya'];
        $wallets = Wallet::where('user_id', Auth::id())->get();

        return view('expenses.index', compact('expenses', 'categories', 'wallets'));
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
            return redirect()->back()->with('error', 'Saldo wallet tidak mencukupi!');
        }

        Expense::create([
            'user_id'     => Auth::id(),
            'wallet_id'   => $request->wallet_id,
            'date'        => $request->date,
            'amount'      => $request->amount,
            'category'    => $request->category,
            'description' => $request->description,
        ]);

        // Kurangi saldo wallet
        $wallet->decrement('balance', $request->amount);

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    public function update(Request $request, Expense $expense)
    {
        abort_if($expense->user_id !== Auth::id(), 403);

        $request->validate([
            'date'        => 'required|date',
            'amount'      => 'required|numeric|min:1',
            'category'    => 'required|string',
            'wallet_id'   => 'required|exists:wallets,id',
            'description' => 'nullable|string',
        ]);

        // Kembalikan saldo wallet lama
        if ($expense->wallet_id) {
            $oldWallet = Wallet::find($expense->wallet_id);
            if ($oldWallet) $oldWallet->increment('balance', $expense->amount);
        }

        $expense->update($request->only('date', 'amount', 'category', 'wallet_id', 'description'));

        // Kurangi saldo wallet baru
        $newWallet = Wallet::findOrFail($request->wallet_id);
        $newWallet->decrement('balance', $request->amount);

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil diupdate!');
    }

    public function destroy(Expense $expense)
    {
        abort_if($expense->user_id !== Auth::id(), 403);

        // Kembalikan saldo wallet
        if ($expense->wallet_id) {
            $wallet = Wallet::find($expense->wallet_id);
            if ($wallet) $wallet->increment('balance', $expense->amount);
        }

        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dihapus!');
    }
}