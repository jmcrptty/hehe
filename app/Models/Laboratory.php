<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    use HasFactory;

    protected $fillable = ['laboratory_name']; // Tambahkan kolom sesuai yang ada di tabel

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
