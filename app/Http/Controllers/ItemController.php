<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\loanStatus;
use App\Models\ItemCategory;
use App\Models\ItemCondition;
use App\Models\storageLocation;
use App\Models\ItemType;
use App\Models\Laboratory;

class ItemController extends Controller
{
    // Menampilkan form input barang
    public function create()
    {
        // Ambil data terkait untuk dropdown di form
        $loanStatuses = LoanStatus::all();
        $itemTypes = ItemType::all();
        $itemCategories = ItemCategory::all();
        $itemConditions = ItemCondition::all();
        $storageLocations = StorageLocation::all();
        $laboratories = Laboratory::all();

        return view('layouts.InputInventaris', compact('loanStatuses', 'itemTypes', 'itemCategories', 'itemConditions', 'storageLocations', 'laboratories'));
    }

    // Menyimpan barang baru ke dalam database
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'item_code' => 'required|string|max:255',
            'item_name' => 'required|string|max:255',
            'loan_status_id' => 'required|integer',
            'item_type_id' => 'required|integer',
            'item_category_id' => 'required|integer',
            'item_condition_id' => 'required|integer',
            'storage_location_id' => 'required|integer',
            'laboratory_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'date_acquired' => 'required|date',
        ]);

        // Simpan data barang
        $item = Item::create([
            'item_code' => $request->item_code,
            'item_name' => $request->item_name,
            'loan_status_id' => $request->loan_status_id,
            'item_type_id' => $request->item_type_id,
            'item_category_id' => $request->item_category_id,
            'item_condition_id' => $request->item_condition_id,
            'storage_location_id' => $request->storage_location_id,
            'laboratory_id' => $request->laboratory_id,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'date_acquired' => $request->date_acquired,
        ]);

        // Cek apakah barang ini habis pakai dan stok sudah mendekati batas minimal
        $itemType = $item->itemType;
        if ($itemType->is_consumable && $item->quantity <= 10) {
            // Kirim notifikasi pengadaan barang
            return redirect()->route('items.create')->with('warning', 'Barang habis pakai dengan kode ' . $item->item_code . ' hampir habis. Silakan lakukan pengadaan.');
        }

        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    // Menampilkan daftar barang
    public function index()
    {
        $items = Item::all();
        return view('layouts.InputInventaris', compact('items'));
    }
}
