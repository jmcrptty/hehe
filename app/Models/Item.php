<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'item_inventory_table_v4';

    protected $fillable = [
        'item_code', 'item_name', 'condition_name', 'loan_status', 'type_name',
        'category_name', 'storage_location_id', 'laboratory_id', 'quantity', 
        'unit', 'date_acquired', 'threshold'
    ];

    // Relasi ke tabel StorageLocation dan Laboratory
    public function storageLocation()
    {
        return $this->belongsTo(StorageLocation::class);
    }

    // Relasi ke tabel Laboratory
    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }
}
