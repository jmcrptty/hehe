<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code', 
        'item_name', 
        'loan_status_id', 
        'item_type_id', 
        'item_category_id', 
        'item_condition_id', 
        'storage_location_id', 
        'laboratory_id', 
        'quantity', 
        'unit', 
        'date_acquired'
    ];

    // Relasi dengan tabel lain jika diperlukan
    public function loanStatus() {
        return $this->belongsTo(LoanStatus::class);
    }

    public function itemType() {
        return $this->belongsTo(ItemType::class);
    }

    public function itemCategory() {
        return $this->belongsTo(ItemCategory::class);
    }

    public function itemCondition() {
        return $this->belongsTo(ItemCondition::class);
    }

    public function storageLocation() {
        return $this->belongsTo(StorageLocation::class);
    }

    public function laboratory() {
        return $this->belongsTo(Laboratory::class);
    }
}
