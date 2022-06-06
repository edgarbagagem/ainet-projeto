<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sessao extends Model
{
    use HasFactory;

    protected $table = 'sessoes';

    public function filmes(){
        return $this->belongsTo(Filme::class);
    }

    public function sala(){
        return $this->belongsTo(Sala::class);
    }

    public function bilhetes(){
        return $this->hasMany(Bilhetes::class);
    }

    
}
