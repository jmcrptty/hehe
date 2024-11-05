<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StorageLocation;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // Ubah ke query builder untuk konsistensi
        $items = DB::table('item_inventory_table_v4')->get();
        return view('layouts.InformasiInventaris', compact('items'));
    }

    public function getDetail($id)
    {
        try {
            $item = Item::with(['storageLocation', 'laboratory'])->findOrFail($id);
            
            // Format data untuk ditampilkan
            $formattedData = [
                'success' => true,
                'data' => [
                    'item_name' => $item->item_name,
                    'item_code' => $item->item_code,
                    'category' => [
                        'name' => $item->category_name,
                        'type' => $item->type_name,
                        'status' => [
                            'condition' => $item->condition_name,
                            'loan' => $item->loan_status
                        ]
                    ],
                    'quantity' => [
                        'current' => $item->quantity,
                        'unit' => $item->unit,
                        'threshold' => $item->threshold
                    ],
                    'location' => [
                        'storage' => $item->storageLocation->name,
                        'laboratory' => $item->laboratory->name
                    ],
                    'date_acquired' => date('d F Y', strtotime($item->date_acquired))
                ]
            ];

            return response()->json($formattedData);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }
}
