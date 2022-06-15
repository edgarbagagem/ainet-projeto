<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bilhete;
use App\Models\Sessao;
use Illuminate\Support\Facades\DB;
use App\Models\Filme;

class EstatisticaController extends Controller
{
    //

    public function index()
    {
        //bilhetes
        $minimo = Bilhete::groupBy('preco_sem_iva')->min('preco_sem_iva');
        $maximo = Bilhete::groupBy('preco_sem_iva')->max('preco_sem_iva');
        $media = Bilhete::groupBy('preco_sem_iva')->avg('preco_sem_iva');

        $media = number_format($media, 2, '.', ' ');

        //filmes

        $filmeMenosVisto = DB::select("SELECT filme_id
        FROM sessoes
        GROUP BY filme_id
        HAVING COUNT(*) =(SELECT min(count) 
        FROM
        (SELECT filme_id, count(*) AS count
        FROM sessoes
        GROUP BY filme_id) AS filme_sessoes)");

        $filmeMaisVisto = DB::select("SELECT filme_id
        FROM sessoes
        GROUP BY filme_id
        HAVING COUNT(*) =(SELECT max(count) 
        FROM
        (SELECT filme_id, count(*) AS count
        FROM sessoes
        GROUP BY filme_id) AS filme_sessoes)");

        $idMenosVisto = $filmeMenosVisto[0]->filme_id;
        $idMaisVisto = $filmeMaisVisto[0]->filme_id;
        //dd($filmeMaisVisto[0]->filme_id, $filmeMenosVisto[0]);

        $filmes = Filme::orWhere('id', '=', $idMenosVisto)
            ->orWhere('id', '=', $idMaisVisto)->get();

        //generos
        $data = DB::table('filmes')
            ->select(
                DB::raw('generos.nome AS genero'),
                DB::raw('count(*) AS count')
            )
            ->join('generos', 'generos.code', '=', 'filmes.genero_code')
            ->groupBy('genero_code')
            ->get();


        $array[] = ['genero', 'count'];

        foreach ($data as $key => $value) {
            $array[++$key] = [$value->genero, $value->count];
        }

        return view('estatisticas.index')->withMinimo($minimo)
            ->withMaximo($maximo)
            ->withMedia($media)
            ->withFilmes($filmes)
            ->withIdMenosVisto($idMenosVisto)
            ->withIdMaisVisto($idMaisVisto)
            ->with('genero', json_encode($array));;
    }
}
