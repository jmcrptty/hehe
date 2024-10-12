<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kategori',
    ];

    public function inventaris()
    {
        return $this->hasMany(Inventaris::class);
    }
}