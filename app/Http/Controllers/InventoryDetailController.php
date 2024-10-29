<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryDetailController extends Controller
{
    public function getDetail($id)
    {
        try {
            Log::info('Accessing detail for item ID: ' . $id);
            
            $item = DB::table('items')
                ->select([
                    'id',
                    'item_code',
                    'item_name',
                    'condition_name',
                    'loan_status',
                    'quantity',
                    'unit'
                ])
                ->where('id', $id)
                ->first();

            Log::info('SQL Query: ' . DB::getQueryLog()[0]['query']);

            if (!$item) {
                Log::warning('Item not found with ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan'
                ], 404);
            }

            Log::info('Item data:', (array) $item);

            $response = [
                'success' => true,
                'data' => [
                    'item_name' => $item->item_name,
                    'item_code' => $item->item_code,
                    'condition_name' => $item->condition_name,
                    'loan_status' => $item->loan_status,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit
                ]
            ];

            Log::info('Sending response:', $response);
            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Error in getDetail: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
}
