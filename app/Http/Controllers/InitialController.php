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

        $substring = $request->substring ?? '';

        $mytime = Carbon\Carbon::now();
        $format1 = 'Y-m-d';
        $format2 = 'H:i:s';
        $data = Carbon\Carbon::parse($mytime)->format($format1);
        $time = Carbon\Carbon::parse($mytime)->format($format2);

        $filmes = Filme::query();

        $filmes = $filmes->select('filmes.id', 'filmes.titulo', 'filmes.sumario', 'filmes.cartaz_url', 'filmes.trailer_url', 'filmes.genero_code')
            ->join('sessoes', 'filmes.id', '=', 'sessoes.filme_id')
            ->where(function ($query) use ($data, $time) {
                $query->where('sessoes.data', '>', $data)
                    ->orWhere(function ($query1) use ($data, $time) {
                        $query1->where('sessoes.data', '=', $data)
                            ->where('sessoes.horario_inicio', '>=', $time);
                    });
            })
            ->groupBy('filmes.id')
            ->groupBy('filmes.titulo')
            ->groupBy('filmes.sumario')
            ->groupBy('filmes.cartaz_url')
            ->groupBy('filmes.trailer_url')
            ->groupBy('filmes.genero_code');

        $generos = Filme::select('filmes.genero_code')
            ->join('sessoes', 'filmes.id', '=', 'sessoes.filme_id')
            ->where(function ($query) use ($data, $time) {
                $query->where('sessoes.data', '>', $data)
                    ->orWhere(function ($query1) use ($data, $time) {
                        $query1->where('sessoes.data', '=', $data)
                            ->where('sessoes.horario_inicio', '>=', $time);
                    });
            })
            ->groupBy('filmes.genero_code')
            ->get();

        $generos = Genero::whereIn('code', $generos)->pluck('nome', 'code');

        if ($genero) {
            $filmes = $filmes->where('genero_code', $genero);
        }

        if ($substring) {
            $filmes = $filmes->where(function ($query) use ($substring) {
                $query->where('titulo', 'LIKE', "%{$substring}%")
                    ->orWhere('sumario', 'like', "%{$substring}%");
            });
        }


        $filmes = $filmes->get();
        return view('filmes.index')->withFilmes($filmes)
            ->withGeneros($generos)
            ->withSelectedGenero($genero);
    }
}
