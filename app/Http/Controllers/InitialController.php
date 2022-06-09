<?php

namespace App\Http\Controllers;

use App\Models\Filme;
use Carbon;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Http\Request;
use App\Models\Genero;


class InitialController extends Controller
{

    public function index(Request $request)
    {

        $genero = $request->genero ?? '';

        $mytime = Carbon\Carbon::now();
        $format1 = 'Y-m-d';
        $format2 = 'H:i:s';
        $data = Carbon\Carbon::parse($mytime)->format($format1);
        $time = Carbon\Carbon::parse($mytime)->format($format2);


        $filmes = Filme::select('filmes.id', 'filmes.titulo', 'filmes.sumario', 'filmes.cartaz_url', 'filmes.trailer_url', 'filmes.genero_code')
            ->join('sessoes', 'filmes.id', '=', 'sessoes.filme_id')
            ->where('sessoes.data', '>=', $data)
            ->where('sessoes.horario_inicio', '>=', $time)
            ->groupBy('filmes.id')
            ->groupBy('filmes.titulo')
            ->groupBy('filmes.sumario')
            ->groupBy('filmes.cartaz_url')
            ->groupBy('filmes.trailer_url')
            ->groupBy('filmes.genero_code')
            ->get();

        $generos = Filme::select('filmes.genero_code')
            ->join('sessoes', 'filmes.id', '=', 'sessoes.filme_id')
            ->where('sessoes.data', '>=', $data)
            ->where('sessoes.horario_inicio', '>=', $time)
            ->groupBy('filmes.genero_code')
            ->get();

        $generos = Genero::whereIn('code', $generos)->pluck('code', 'nome');

        if ($genero) {
            $filmes = Filme::select('filmes.id', 'filmes.titulo', 'filmes.sumario', 'filmes.cartaz_url', 'filmes.trailer_url', 'filmes.genero_code')
                ->join('sessoes', 'filmes.id', '=', 'sessoes.filme_id')
                ->where('sessoes.data', '>=', $data)
                ->where('sessoes.horario_inicio', '>=', $time)
                ->where('genero_code', $genero)
                ->groupBy('filmes.id')
                ->groupBy('filmes.titulo')
                ->groupBy('filmes.sumario')
                ->groupBy('filmes.cartaz_url')
                ->groupBy('filmes.trailer_url')
                ->groupBy('filmes.genero_code')
                ->get();
        }

        return view('filmes.index')->withFilmes($filmes)
            ->withGeneros($generos)
            ->withSelectedGenero($genero);
    }
}
