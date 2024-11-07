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
        'threshold'
    ];

    // Relasi ke tabel StorageLocation
    public function storageLocation()
    {
        return $this->belongsTo(StorageLocation::class);
    }

    // Relasi ke tabel Laboratory
    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    // Relasi ke tabel Peminjaman
    public function peminjaman()
    {
        return $this->belongsToMany(Peminjaman::class, 'peminjaman_items')
                    ->withPivot('quantity', 'status')
                    ->withTimestamps();
    }

    // Method untuk update status peminjaman
    public function updateLoanStatus($quantity_requested)
    {
        if ($this->type_name == 'habis_pakai') {
            $this->quantity -= $quantity_requested;
            if ($this->quantity <= 0) {
                $this->loan_status = 'kosong';
            }
        } else {
            $this->loan_status = 'dipinjam';
        }
        $this->save();
    }

    // Method untuk cek apakah barang bisa dipinjam
    public function isAvailable()
    {
        return $this->loan_status === 'tersedia' && $this->quantity > 0;
    }

    // Method untuk cek apakah stok mencukupi
    public function hasEnoughStock($requestedQuantity)
    {
        return $this->quantity >= $requestedQuantity;
    }

    // Method untuk cek apakah barang perlu direstok
    public function needsRestock()
    {
        return $this->quantity <= $this->threshold;
    }

    // Accessor untuk format tanggal
    public function getDateAcquiredFormattedAttribute()
    {
        return \Carbon\Carbon::parse($this->date_acquired)->format('d/m/Y');
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'tersedia' => 'success',
            'dipinjam' => 'warning',
            'kosong' => 'danger'
        ];

        return '<span class="badge bg-' . $colors[$this->loan_status] . '">' 
               . ucfirst($this->loan_status) . 
               '</span>';
    }

    // Accessor untuk tipe badge
    public function getTypeBadgeAttribute()
    {
        $color = $this->type_name === 'jangka_panjang' ? 'info' : 'warning';
        return '<span class="badge bg-' . $color . '">' 
               . ucfirst(str_replace('_', ' ', $this->type_name)) . 
               '</span>';
    }
}
