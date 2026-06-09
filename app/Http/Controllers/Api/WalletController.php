<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\WalletTransfer;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $wallets = Wallet::where('user_id', $request->user()->id)->get();

        return response()->json([
            'success' => true,
            'data'    => $wallets
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'type'        => 'required|in:cash,bank,credit_card,e_wallet',
            'balance'     => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $wallet = Wallet::create([
            'user_id'     => $request->user()->id,
            'name'        => $request->name,
            'type'        => $request->type,
            'balance'     => $request->balance,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Wallet berhasil ditambahkan!',
            'data'    => $wallet
        ], 201);
    }

    public function update(Request $request, Wallet $wallet)
    {
        abort_if($wallet->user_id !== $request->user()->id, 403);

        $request->validate([
            'name'        => 'required|string',
            'type'        => 'required|in:cash,bank,credit_card,e_wallet',
            'balance'     => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $wallet->update($request->only('name', 'type', 'balance', 'description'));

        return response()->json([
            'success' => true,
            'message' => 'Wallet berhasil diupdate!',
            'data'    => $wallet
        ]);
    }

    public function destroy(Request $request, Wallet $wallet)
    {
        abort_if($wallet->user_id !== $request->user()->id, 403);
        $wallet->delete();

        return response()->json([
            'success' => true,
            'message' => 'Wallet berhasil dihapus!'
        ]);
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

        abort_if($fromWallet->user_id !== $request->user()->id, 403);

        if ($fromWallet->balance < $request->amount) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo wallet tidak mencukupi!'
            ], 400);
        }

        $fromWallet->decrement('balance', $request->amount);
        $toWallet->increment('balance', $request->amount);

        WalletTransfer::create([
            'user_id'        => $request->user()->id,
            'from_wallet_id' => $request->from_wallet_id,
            'to_wallet_id'   => $request->to_wallet_id,
            'amount'         => $request->amount,
            'date'           => $request->date,
            'description'    => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transfer berhasil!'
        ]);
    }
}