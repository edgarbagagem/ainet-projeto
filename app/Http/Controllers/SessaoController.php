<?php

namespace App\Http\Controllers;

use App\Models\Sessao;
use Illuminate\Http\Request;
use Carbon;
use App\Models\Filme;

class SessaoController extends Controller
{
    public function index(Request $request)
    {
        $currentTime = Carbon\Carbon::now();
        $format1 = 'Y-m-d';
        $format2 = 'H:i:s';
        $data = Carbon\Carbon::parse($currentTime)->format($format1);
        $time = Carbon\Carbon::parse($currentTime)->format($format2);


        $sessoes = Sessao::select('filmes.id AS id', 'filmes.titulo AS titulo', 'sessoes.data', 'sessoes.horario_inicio', 'salas.nome AS sala')
            ->join('filmes', 'filmes.id', '=', 'sessoes.filme_id')
            ->join('salas', 'salas.id', '=', 'sessoes.sala_id')
            ->where('sessoes.data', '>=', $data)
            ->where('sessoes.horario_inicio', '>=', $time)
            ->paginate(10);
        return view('sessoes.index', compact('sessoes'));
    }

    public function sessoesFilme($id)
    {
        $currentTime = Carbon\Carbon::now();
        $format1 = 'Y-m-d';
        $format2 = 'H:i:s';
        $data = Carbon\Carbon::parse($currentTime)->format($format1);
        $time = Carbon\Carbon::parse($currentTime)->format($format2);

        $sessoes = Sessao::select('filmes.titulo AS titulo', 'sessoes.data', 'sessoes.horario_inicio', 'salas.nome AS sala')
            ->join('filmes', 'filmes.id', '=', 'sessoes.filme_id')
            ->join('salas', 'salas.id', '=', 'sessoes.sala_id')
            ->where('sessoes.data', '>=', $data)
            ->where('sessoes.horario_inicio', '>=', $time)
            ->where('sessoes.filme_id', '=', $id)
            ->paginate(10);
        return view('sessoes.index')->withId($id)
            ->withSessoes($sessoes);
    }
}
