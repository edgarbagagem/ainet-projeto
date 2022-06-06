<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bilhete extends Model
{
    use HasFactory;

    
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function recibo(){
        return $this->belongsTo(Recibo::class);
    }

    public function sessao(){
        return $this->belongsTo(Sessao::class);
    }
}
