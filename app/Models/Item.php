<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'item_inventory_table_v4';

    protected $fillable = [
        'item_code',
        'item_name',
        'condition_name',
        'loan_status',
        'type_name',
        'category_name',
        'storage_location_id',
        'laboratory_id',
        'quantity',
        'unit',
        'date_acquired',
        'threshold',
        'price',
        'total_price',
        'usage',
        'notes',
        'image',
    ];

    public function storageLocation()
    {
        return $this->belongsTo(StorageLocation::class);
    }

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function peminjaman()
    {
        return $this->belongsToMany(Peminjaman::class, 'peminjaman_items')
            ->withPivot('quantity', 'status')
            ->withTimestamps();
    }

    // Accessor untuk properti `name`
    public function getNameAttribute()
    {
        return $this->item_name;
    }
}
