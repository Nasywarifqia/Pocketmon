<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Brankas;

class BrankasController extends Controller
{
    public function index()
    {
        $brankas = Brankas::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('brankas.index', compact('brankas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name'       => 'required|string',
            'target_price'    => 'required|numeric|min:1',
            'collected_amount'=> 'nullable|numeric|min:0',
            'deadline'        => 'nullable|date',
            'priority'        => 'required|in:tinggi,sedang,rendah',
            'description'     => 'nullable|string',
        ]);

        $collected = $request->collected_amount ?? 0;
        $status = $collected >= $request->target_price ? 'tercapai' : 'belum_tercapai';

        Brankas::create([
            'user_id'          => Auth::id(),
            'item_name'        => $request->item_name,
            'target_price'     => $request->target_price,
            'collected_amount' => $collected,
            'deadline'         => $request->deadline,
            'priority'         => $request->priority,
            'description'      => $request->description,
            'status'           => $status,
        ]);

        return redirect()->route('brankas.index')->with('success', 'Brankas berhasil ditambahkan!');
    }

    public function update(Request $request, Brankas $branka)
    {
        abort_if($branka->user_id !== Auth::id(), 403);

        $request->validate([
            'item_name'        => 'required|string',
            'target_price'     => 'required|numeric|min:1',
            'collected_amount' => 'nullable|numeric|min:0',
            'deadline'         => 'nullable|date',
            'priority'         => 'required|in:tinggi,sedang,rendah',
            'description'      => 'nullable|string',
        ]);

        $collected = $request->collected_amount ?? 0;
        $status = $collected >= $request->target_price ? 'tercapai' : 'belum_tercapai';

        $branka->update([
            'item_name'        => $request->item_name,
            'target_price'     => $request->target_price,
            'collected_amount' => $collected,
            'deadline'         => $request->deadline,
            'priority'         => $request->priority,
            'description'      => $request->description,
            'status'           => $status,
        ]);

        return redirect()->route('brankas.index')->with('success', 'Brankas berhasil diupdate!');
    }

    public function destroy(Brankas $branka)
    {
        abort_if($branka->user_id !== Auth::id(), 403);
        $branka->delete();
        return redirect()->route('brankas.index')->with('success', 'Brankas berhasil dihapus!');
    }
}