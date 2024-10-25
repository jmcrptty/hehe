<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class InventoryDetailController extends Controller
{
    public function show($id)
    {
        try {
            $item = Item::with(['storageLocation', 'laboratory'])->findOrFail($id);
            
            return response()->json([
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
                        'storage' => $item->storageLocation->name ?? 'Tidak ada',
                        'laboratory' => $item->laboratory->name ?? 'Tidak ada'
                    ],
                    'date_acquired' => date('d F Y', strtotime($item->date_acquired))
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }
}
