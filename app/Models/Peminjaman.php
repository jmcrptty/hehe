<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'keterangan',
    ];

    // Tambahkan konstanta untuk status
    const STATUS_MENUNGGU = 'menunggu';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_DITOLAK = 'ditolak';
    const STATUS_MENUNGGU_PENGEMBALIAN = 'menunggu_pengembalian';
    const STATUS_TERLAMBAT = 'terlambat';
    const STATUS_SELESAI = 'selesai';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'peminjaman_items')
                    ->withPivot('quantity', 'status')
                    ->withTimestamps();
    }

    // Scope untuk cek tunggakan
    public function scopeHasOutstandingLoan($query, $userId)
    {
        return $query->where('user_id', $userId)
                     ->where('status', 'dipinjam')
                     ->exists();
    }

    // Tambahkan scope untuk peminjaman yang belum dikembalikan
    public function scopeUnreturned($query)
    {
        return $query->whereIn('status', [self::STATUS_MENUNGGU_PENGEMBALIAN, self::STATUS_TERLAMBAT]);
    }

    // Tambahkan relasi untuk tracking admin yang memproses
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function returnedBy()
    {
        return $this->belongsTo(User::class, 'returned_by');
    }
}
