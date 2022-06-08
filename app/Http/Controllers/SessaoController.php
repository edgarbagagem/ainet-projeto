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


        $sessoes = Sessao::select('filmes.id AS id', 'filmes.titulo AS titulo', 'sessoes.data', 'sessoes.horario_inicio', 'salas.nome AS sala')
            ->join('filmes', 'filmes.id', '=', 'sessoes.filme_id')
            ->join('salas', 'salas.id', '=', 'sessoes.sala_id')
            ->where('sessoes.data', '>=', $currentTime)
            ->paginate(10);
        return view('sessoes.index', compact('sessoes'));
    }

    public function sessoesFilme($id)
    {
        $currentTime = Carbon\Carbon::now();


        $sessoes = Sessao::select('filmes.titulo AS titulo', 'sessoes.data', 'sessoes.horario_inicio', 'salas.nome AS sala')
            ->join('filmes', 'filmes.id', '=', 'sessoes.filme_id')
            ->join('salas', 'salas.id', '=', 'sessoes.sala_id')
            ->where('sessoes.data', '>=', $currentTime)
            ->where('sessoes.filme_id', '=', $id)
            ->paginate(10);
        return view('sessoes.index')->withId($id)
            ->withSessoes($sessoes);
    }
}
