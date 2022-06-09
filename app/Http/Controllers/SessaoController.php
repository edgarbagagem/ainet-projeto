<?php

namespace App\Http\Controllers;

use App\Models\Sessao;
use Illuminate\Http\Request;
use Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Bilhete;

class SessaoController extends Controller
{
    public function index(Request $request)
    {
        $currentTime = Carbon\Carbon::now();
        $format1 = 'Y-m-d';
        $format2 = 'H:i:s';
        $data = Carbon\Carbon::parse($currentTime)->format($format1);
        $time = Carbon\Carbon::parse($currentTime)->format($format2);

        $sessoes = Sessao::query();

        $sessoes = $sessoes->select('sessoes.id AS id', 'filmes.titulo AS titulo', 'sessoes.data', 'sessoes.horario_inicio', 'salas.nome AS sala', 'salas.id AS sala_id')
            ->join('filmes', 'filmes.id', '=', 'sessoes.filme_id')
            ->join('salas', 'salas.id', '=', 'sessoes.sala_id')
            ->where('sessoes.data', '>=', $data)
            ->where('sessoes.horario_inicio', '>=', $time)
            ->paginate(10);



        foreach ($sessoes as $sessao) {
            $totalLugares = DB::table('lugares')->where('sala_id', '=', $sessao->sala_id)->count();
            $sessao->totalLugares = $totalLugares;
            $lugaresPorSessao = Bilhete::query();
            $sessao->lugaresOcupados = $lugaresPorSessao->where('sessao_id', '=', $sessao->id)->count();
        }

        return view('sessoes.index')->withSessoes($sessoes);
    }

    public function sessoesFilme($id)
    {
        $currentTime = Carbon\Carbon::now();
        $format1 = 'Y-m-d';
        $format2 = 'H:i:s';
        $data = Carbon\Carbon::parse($currentTime)->format($format1);
        $time = Carbon\Carbon::parse($currentTime)->format($format2);



        $sessoes = Sessao::query();

        $sessoes = $sessoes->select('sessoes.id AS id', 'filmes.titulo AS titulo', 'sessoes.data', 'sessoes.horario_inicio', 'salas.nome AS sala', 'salas.id AS sala_id')
            ->join('filmes', 'filmes.id', '=', 'sessoes.filme_id')
            ->join('salas', 'salas.id', '=', 'sessoes.sala_id')
            ->where('sessoes.data', '>=', $data)
            ->where('sessoes.horario_inicio', '>=', $time)
            ->where('sessoes.filme_id', '=', $id)
            ->paginate(10);


        $lugaresPorSessao = Sessao::query();
        foreach ($sessoes as $sessao) {
            $totalLugares = DB::table('lugares')->where('sala_id', '=', $sessao->sala_id)->count();
            $sessao->totalLugares = $totalLugares;
            $lugaresPorSessao = Bilhete::query();
            $sessao->lugaresOcupados = $lugaresPorSessao->where('sessao_id', '=', $sessao->id)->count();
        }

        return view('sessoes.index')->withId($id)
            ->withSessoes($sessoes);
    }
}
