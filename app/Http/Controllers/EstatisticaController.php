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

        $filmesMenosVistos = DB::select("SELECT sessoes.filme_id
        FROM bilhetes
        JOIN sessoes ON sessoes.id = bilhetes.sessao_id
        JOIN filmes ON sessoes.filme_id = filmes.id
        GROUP BY sessoes.filme_id
        HAVING COUNT(*)=(
        SELECT min(count)
        FROM
        (SELECT count(*) as count, sessoes.filme_id
        FROM `bilhetes`
        JOIN sessoes ON sessoes.id = bilhetes.sessao_id
        GROUP BY sessoes.filme_id) AS count_bilhetes_filme)");

        $filmesMaisVistos = DB::select("SELECT sessoes.filme_id
        FROM bilhetes
        JOIN sessoes ON sessoes.id = bilhetes.sessao_id
        JOIN filmes ON sessoes.filme_id = filmes.id
        GROUP BY sessoes.filme_id
        HAVING COUNT(*)=(
        SELECT MAX(count)
        FROM
        (SELECT count(*) as count, sessoes.filme_id
        FROM `bilhetes`
        JOIN sessoes ON sessoes.id = bilhetes.sessao_id
        GROUP BY sessoes.filme_id) AS count_bilhetes_filme)");

        $idsMenosVistos = array();

        foreach ($filmesMenosVistos as $filme) {
            array_push($idsMenosVistos, $filme->filme_id);
        }

        $idsMaisVistos = array();

        foreach ($filmesMaisVistos as $filme) {
            array_push($idsMaisVistos, $filme->filme_id);
        }

        //dd($idsMaisVistos, $idsMenosVistos);

        $filmes = Filme::orWhereIn('id', $idsMenosVistos)
            ->orWhereIn('id', $idsMaisVistos)->get();

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
            ->withIdsMenosVistos($idsMenosVistos)
            ->withIdsMaisVistos($idsMaisVistos)
            ->with('genero', json_encode($array));;
    }
}
