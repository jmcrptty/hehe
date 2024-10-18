<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StorageLocation;
use App\Models\Laboratory;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Menampilkan form input barang
    public function create()
    {
        $storageLocations = StorageLocation::all();
        $laboratories = Laboratory::all();

        return view('layouts.inputinventaris', compact('storageLocations', 'laboratories'));
    }

    // Menyimpan barang baru ke dalam database
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'item_code' => 'required|string|max:255',
            'item_name' => 'required|string|max:255',
            'condition_name' => 'required|in:Baik,Rusak',
            'loan_status' => 'required|in:Dipinjam,Tersedia',
            'type_name' => 'required|in:Barang Baru,Barang Lama',
            'category_name' => 'required|in:Barang Jangka Panjang,Barang Habis Pakai',
            'storage_location_id' => 'required|integer',
            'laboratory_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'date_acquired' => 'required|date',
            'threshold' => 'required_if:category_name,Barang Habis Pakai|nullable|integer|min:1', // Validasi threshold
        ]);

    
        Item::create($request->all());

        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan.');
    }


    public function index()
    {
        $items = Item::all();
        $storageLocations = StorageLocation::all(); // Fetch storage locations
        $laboratories = Laboratory::all();
        return view('layouts.inputinventaris', compact('items','storageLocations', 'laboratories'));
    }
}
