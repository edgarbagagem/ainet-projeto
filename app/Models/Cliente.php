<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recibos()
    {
        return $this->hasMany(Recibos::class);
    }

    public function bilhetes()
    {
        return $this->hasMany(Bilhetes::class);
    }
}
