<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filme extends Model
{
    use HasFactory;

    public function generos(){
        $this->belongsTo(Genero::class);
    }

    public function sessoes(){
        $this->hasMany(Sessao::class);
    }
}
