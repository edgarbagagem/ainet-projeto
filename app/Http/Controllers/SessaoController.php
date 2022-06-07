<?php

namespace App\Http\Controllers;

use App\Models\Sessao;
use Illuminate\Http\Request;
use Carbon;

class SessaoController extends Controller
{
    public function index(Request $request)
    {
        $currentTime = Carbon\Carbon::now();


        $sessoes = Sessao::select('filmes.titulo AS titulo', 'sessoes.data', 'sessoes.horario_inicio', 'salas.nome AS sala')
            ->join('filmes', 'filmes.id', '=', 'sessoes.filme_id')
            ->join('salas', 'salas.id', '=', 'sessoes.sala_id')
            ->where('sessoes.data', '>=', $currentTime)
            ->paginate(10);
        return view('sessoes.index', compact('sessoes'));
    }
}
