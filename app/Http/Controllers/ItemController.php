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
        $request->validate([
            'item_code' => 'required|unique:item_inventory_table_v4,item_code',
            'item_name' => 'required',
            'condition_name' => 'required',
            'loan_status' => 'required',
            'type_name' => 'required',
            'category_name' => 'required',
            'storage_location_id' => 'required|exists:storage_locations,id',
            'laboratory_id' => 'required|exists:laboratories,id',
            'quantity' => 'required|numeric|min:1',
            'unit' => 'required',
            'date_acquired' => 'required|date',
            'threshold' => 'nullable|numeric|min:1'
        ]);

        try {
            DB::beginTransaction();

            $item = new Item();
            $item->item_code = $request->item_code;
            $item->item_name = $request->item_name;
            $item->condition_name = $request->condition_name;
            $item->loan_status = $request->loan_status;
            $item->type_name = $request->type_name;
            $item->category_name = $request->category_name;
            $item->storage_location_id = $request->storage_location_id;
            $item->laboratory_id = $request->laboratory_id;
            $item->quantity = $request->quantity;
            $item->unit = $request->unit;
            $item->date_acquired = $request->date_acquired;
            $item->threshold = $request->threshold;
            
            $item->save();

            DB::commit();
            return redirect()->back()->with('success', 'Item berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
