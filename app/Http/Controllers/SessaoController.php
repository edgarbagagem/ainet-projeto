<?php

namespace App\Http\Controllers;

use App\Models\Sessao;
use Carbon;

class SessaoController extends Controller
{
    public function index()
    {
        $currentTime = Carbon\Carbon::now();


        $sessoes = Sessao::select('filmes.titulo', 'sessoes.data', 'sessoes.horario_inicio', 'salas.nome')
            ->join('filmes', 'filmes.id', '=', 'sessoes.filme_id')
            ->join('salas', 'salas.id', '=', 'sessoes.sala_id')
            ->where('sessoes.data', '>=', $currentTime)
            ->get();

        return view('sessoes.index', compact('sessoes'));
    }
}
