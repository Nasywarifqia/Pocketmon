<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brankas;

class BrankasController extends Controller
{
    public function index(Request $request)
    {
        $brankas = Brankas::where('user_id', $request->user()->id)
            ->orderByRaw("FIELD(priority, 'tinggi', 'sedang', 'rendah')")
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $brankas
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name'        => 'required|string',
            'target_price'     => 'required|numeric|min:1',
            'collected_amount' => 'nullable|numeric|min:0',
            'deadline'         => 'nullable|date',
            'priority'         => 'required|in:tinggi,sedang,rendah',
            'description'      => 'nullable|string',
        ]);

        $collected = $request->collected_amount ?? 0;
        $status    = $collected >= $request->target_price ? 'tercapai' : 'belum_tercapai';

        $brankas = Brankas::create([
            'user_id'          => $request->user()->id,
            'item_name'        => $request->item_name,
            'target_price'     => $request->target_price,
            'collected_amount' => $collected,
            'deadline'         => $request->deadline,
            'priority'         => $request->priority,
            'description'      => $request->description,
            'status'           => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Brankas berhasil ditambahkan!',
            'data'    => $brankas
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $brankas = Brankas::findOrFail($id);
        abort_if($brankas->user_id !== $request->user()->id, 403);

        $request->validate([
            'item_name'        => 'required|string',
            'target_price'     => 'required|numeric|min:1',
            'collected_amount' => 'nullable|numeric|min:0',
            'deadline'         => 'nullable|date',
            'priority'         => 'required|in:tinggi,sedang,rendah',
            'description'      => 'nullable|string',
        ]);

        $collected = $request->collected_amount ?? 0;
        $status    = $collected >= $request->target_price ? 'tercapai' : 'belum_tercapai';

        $brankas->update([
            'item_name'        => $request->item_name,
            'target_price'     => $request->target_price,
            'collected_amount' => $collected,
            'deadline'         => $request->deadline,
            'priority'         => $request->priority,
            'description'      => $request->description,
            'status'           => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Brankas berhasil diupdate!',
            'data'    => $brankas
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $brankas = Brankas::findOrFail($id);
        abort_if($brankas->user_id !== $request->user()->id, 403);
        $brankas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Brankas berhasil dihapus!'
        ]);
    }
}