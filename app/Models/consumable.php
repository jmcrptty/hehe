<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class consumable extends Model
{
    public function consumable()
{
    return $this->hasOne(Consumable::class);
}
    use HasFactory;
}
