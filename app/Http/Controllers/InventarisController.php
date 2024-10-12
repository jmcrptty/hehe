<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemType;
use App\Models\LoanStatus;
use App\Models\ItemCategory;
use App\Models\ItemCondition;
use App\Models\StorageLocation;
use App\Models\Laboratory;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function create()
    {
        $loanStatuses = LoanStatus::all();
        $itemTypes = ItemType::all();
        $itemCategories = ItemCategory::all();
        $itemConditions = ItemCondition::all();
        $storageLocations = StorageLocation::all();
        $laboratories = Laboratory::all();

        return view('items.create', compact('loanStatuses', 'itemTypes', 'itemCategories', 'itemConditions', 'storageLocations', 'laboratories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'item_code' => 'required|unique:items',
            'item_name' => 'required',
            'loan_status_id' => 'required|exists:loan_statuses,id',
            'item_type_id' => 'required|exists:item_types,id',
            'item_category_id' => 'required|exists:item_categories,id',
            'item_condition_id' => 'required|exists:item_conditions,id',
            'storage_location_id' => 'required|exists:storage_locations,id',
            'laboratory_id' => 'required|exists:laboratories,id',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required',
            'date_acquired' => 'required|date',
            'initial_quantity' => 'required_if:is_consumable,1|integer|min:0',
            'threshold' => 'required_if:is_consumable,1|integer|min:1|max:100',
        ]);

        $item = Item::create($validatedData);

        if ($request->is_consumable) {
            // Simpan data tambahan untuk barang habis pakai
            $item->consumable()->create([
                'initial_quantity' => $request->initial_quantity,
                'threshold' => $request->threshold,
            ]);

            $this->checkConsumableThreshold($item);
        }

        return redirect()->route('items.index')->with('success', 'Item berhasil ditambahkan.');
    }

    private function checkConsumableThreshold($item)
    {
        $consumable = $item->consumable;
        $currentPercentage = ($item->quantity / $consumable->initial_quantity) * 100;

        if ($currentPercentage <= $consumable->threshold) {
            // Di sini Anda bisa menambahkan logika untuk mengirim notifikasi atau membuat peringatan
            // Contoh sederhana:
            session()->flash('warning', "Peringatan: Stok {$item->item_name} sudah mencapai {$currentPercentage}% dari stok awal.");
        }
    }
}