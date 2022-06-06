<?php

namespace App\Http\Controllers;

use App\Models\Filme;
use Carbon;
use Illuminate\Http\Request;


class InitialController extends Controller
{

    public function index(Request $request){

    $mytime = Carbon\Carbon::now();
    $filmes = Filme::join('sessoes', 'filmes.id', '=', 'sessoes.filme_id')
                        ->where('sessoes.data', '>=', $mytime)
                        ->paginate();
                                      
        
        return view('filmes.index', compact('filmes'));
    }
}
