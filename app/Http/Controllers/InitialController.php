<?php

namespace App\Http\Controllers;

use App\Models\Filme;
use Carbon;
use Illuminate\Http\Request;


class InitialController extends Controller
{

    public function index(Request $request)
    {

        $mytime = Carbon\Carbon::now();
        /*$filmes = Filme::join('sessoes', 'filmes.id', '=', 'sessoes.filme_id')
                        ->where('sessoes.data', '>=', $mytime)
                        ->distinct('filmes.titulo')
                        ->paginate();*/


        $filmes = Filme::select('filmes.titulo', 'filmes.sumario', 'filmes.cartaz_url', 'filmes.trailer_url')
            ->join('sessoes', 'filmes.id', '=', 'sessoes.filme_id')
            ->where('sessoes.data', '>=', $mytime)
            ->groupBy('filmes.titulo')
            ->groupBy('filmes.sumario')
            ->groupBy('filmes.cartaz_url')
            ->groupBy('filmes.trailer_url')
            ->get();


        return view('filmes.index', compact('filmes'));
    }
}
