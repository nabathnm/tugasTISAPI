<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    // GET /api/items - Ambil semua item milik user
    public function index()
    {
        $items = Item::where('user_id', Auth::id())->latest()->get();
        return response()->json(['data' => $items]);
    }

    // POST /api/items - Tambah item baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'quantity'     => 'integer|min:1',
            'unit'         => 'nullable|string|max:50',
            'note'         => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $item = Item::create([
            'user_id'  => Auth::id(),
            'name'     => $request->name,
            'quantity' => $request->quantity ?? 1,
            'unit'     => $request->unit,
            'note'     => $request->note,
        ]);

        return response()->json(['message' => 'Item berhasil ditambahkan.', 'data' => $item], 201);
    }

    // GET /api/items/{id} - Ambil item tertentu
    public function show($id)
    {
        $item = Item::where('id', $id)->where('user_id', Auth::id())->first();
        if (!$item) return response()->json(['message' => 'Item tidak ditemukan.'], 404);
        return response()->json(['data' => $item]);
    }

    // PUT /api/items/{id} - Update item
    public function update(Request $request, $id)
    {
        $item = Item::where('id', $id)->where('user_id', Auth::id())->first();
        if (!$item) return response()->json(['message' => 'Item tidak ditemukan.'], 404);

        $validator = Validator::make($request->all(), [
            'name'         => 'string|max:255',
            'quantity'     => 'integer|min:1',
            'unit'         => 'nullable|string|max:50',
            'is_purchased' => 'boolean',
            'note'         => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $item->update($request->only(['name','quantity','unit','is_purchased','note']));
        return response()->json(['message' => 'Item berhasil diperbarui.', 'data' => $item]);
    }

    // DELETE /api/items/{id} - Hapus item
    public function destroy($id)
    {
        $item = Item::where('id', $id)->where('user_id', Auth::id())->first();
        if (!$item) return response()->json(['message' => 'Item tidak ditemukan.'], 404);
        $item->delete();
        return response()->json(['message' => 'Item berhasil dihapus.']);
    }
}
