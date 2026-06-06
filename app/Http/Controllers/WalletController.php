<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\WalletTransfer;

class WalletController extends Controller
{
    public function index()
    {
        $wallets = Wallet::where('user_id', Auth::id())->get();
        return view('wallets.index', compact('wallets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'type'        => 'required|in:cash,bank,credit_card,e_wallet',
            'balance'     => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Wallet::create([
            'user_id'     => Auth::id(),
            'name'        => $request->name,
            'type'        => $request->type,
            'balance'     => $request->balance,
            'description' => $request->description,
        ]);

        return redirect()->route('wallets.index')->with('success', 'Wallet berhasil ditambahkan!');
    }

    public function update(Request $request, Wallet $wallet)
    {
        abort_if($wallet->user_id !== Auth::id(), 403);

        $request->validate([
            'name'        => 'required|string',
            'type'        => 'required|in:cash,bank,credit_card,e_wallet',
            'balance'     => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $wallet->update($request->only('name', 'type', 'balance', 'description'));

        return redirect()->route('wallets.index')->with('success', 'Wallet berhasil diupdate!');
    }

    public function destroy(Wallet $wallet)
    {
        abort_if($wallet->user_id !== Auth::id(), 403);
        $wallet->delete();
        return redirect()->route('wallets.index')->with('success', 'Wallet berhasil dihapus!');
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'from_wallet_id' => 'required|exists:wallets,id',
            'to_wallet_id'   => 'required|exists:wallets,id|different:from_wallet_id',
            'amount'         => 'required|numeric|min:1',
            'date'           => 'required|date',
            'description'    => 'nullable|string',
        ]);

        $fromWallet = Wallet::findOrFail($request->from_wallet_id);
        $toWallet   = Wallet::findOrFail($request->to_wallet_id);

        abort_if($fromWallet->user_id !== Auth::id(), 403);
        abort_if($toWallet->user_id !== Auth::id(), 403);

        if ($fromWallet->balance < $request->amount) {
            return redirect()->back()->with('error', 'Saldo wallet tidak mencukupi!');
        }

        $fromWallet->decrement('balance', $request->amount);
        $toWallet->increment('balance', $request->amount);

        WalletTransfer::create([
            'user_id'        => Auth::id(),
            'from_wallet_id' => $request->from_wallet_id,
            'to_wallet_id'   => $request->to_wallet_id,
            'amount'         => $request->amount,
            'date'           => $request->date,
            'description'    => $request->description,
        ]);

        return redirect()->route('wallets.index')->with('success', 'Transfer berhasil!');
    }
}