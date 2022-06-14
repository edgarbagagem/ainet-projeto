<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sala;
use App\Models\Filme;
use App\Models\Sessao;
use App\Models\Genero;
use Carbon;
use App\Http\Requests\FilmePost;
use App\Http\Requests\SessaoPost;

class ConfigurationController extends Controller
{

    public $alphabet = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

    public function index(Request $request)
    {

        $substring = $request->substring ?? '';

        $configuracao = DB::table('configuracao')->first();

        $salas = Sala::query();
        $salas = $salas->paginate(10, '*', 'salas');

        foreach ($salas as $sala) {
            $totalLugares = DB::table('lugares')->where('sala_id', '=', $sala->id)->count();
            $sala->lugares = $totalLugares;
        }

        $filmes = Filme::query();

        if ($substring) {
            $filmes = $filmes->where('titulo', 'LIKE', "%{$substring}%");
        }

        $filmes = $filmes->paginate(10, '*', 'filmes');

        foreach ($filmes as $filme) {
            $filme->sessaoCount = Sessao::where('sessoes.filme_id', '=', $filme->id)->count();
        }

        return view('administracao.negocio')->withConfiguracao($configuracao)
            ->withSalas($salas)
            ->withFilmes($filmes);
    }

    public function save_config(Request $request)
    {
        DB::table('configuracao')->where('id', '=', 1)
            ->update(['preco_bilhete_sem_iva' => $request->preco, 'percentagem_iva' => $request->iva]);

        return redirect()->route('config.index')->with('alert-msg', 'Configuração de preços atualizada')
            ->with('alert-type', 'success');;
    }

    public function create_sala()
    {
        $sala = new Sala;
        return view('administracao.salas.create')->withSala($sala);
    }

    public function store_sala(Request $request)
    {

        try {
            //Sala
            $newSala = new Sala;
            $newSala->nome = $request->nome;
            $newSala->save();
            //Lugares
            for ($i = 0; $i < $request->filas; $i++) {
                for ($j = 0; $j < $request->colunas; $j++) {
                    DB::table('lugares')->insert(['sala_id' => $newSala->id, 'fila' => $this->alphabet[$i], 'posicao' => $j + 1]);
                }
            }

            return redirect()->route('config.index')->with('alert-msg', 'Sala Criada Com Sucesso')
                ->with('alert-type', 'success');
        } catch (\Throwable $th) {
            return redirect()->route('config.index')->with('alert-msg', 'Não foi possível criar a sala. Erro: ' . $th->errorInfo[2])
                ->with('alert-type', 'danger');
        }
    }

    public function edit_sala(Sala $sala)
    {
        $lugares = DB::table('lugares')->where('sala_id', '=', $sala->id)->count();
        $colunas = DB::table('lugares')->where('sala_id', '=', $sala->id)->groupBy('fila')->count();

        $sala->filas = $lugares / $colunas;
        $sala->colunas = $colunas;

        return view('administracao.salas.edit')->withSala($sala);
    }

    public function update_sala(Request $request, Sala $sala)
    {
        DB::table('lugares')->where('sala_id', '=', $sala->id)->delete();

        $sala->nome = $request->nome;
        $sala->save();

        for ($i = 0; $i < $request->filas; $i++) {
            for ($j = 0; $j < $request->colunas; $j++) {
                DB::table('lugares')->insert(['sala_id' => $sala->id, 'fila' => $this->alphabet[$i], 'posicao' => $j + 1]);
            }
        }

        return redirect()->route('config.index')->with('alert-msg', 'Sala atualizada com sucesso')
            ->with('alert-type', 'success');
    }

    public function delete_sala(Sala $sala)
    {
        //Guardar filas e colunas na coluna custom da tabela da sala 
        //para no caso de se restorar a sala saber o numero de lugares a serem criados de volta
        $oldID = $sala->id;

        $lugares = DB::table('lugares')->where('sala_id', '=', $sala->id)->count();
        $colunas = DB::table('lugares')->where('sala_id', '=', $sala->id)->groupBy('fila')->count();

        $arr_tojson = array(
            'filas' => $lugares / $colunas,
            'colunas' => $colunas,
        );
        $custom = json_encode($arr_tojson);

        $sala->custom = $custom;
        $sala->save();

        DB::table('lugares')->where('sala_id', '=', $sala->id)->delete();

        try {
            $sala->delete();

            return redirect()->route('config.index')
                ->with('alert-msg', 'Sala foi apagada com sucesso!')
                ->with('alert-type', 'success');
        } catch (\Throwable $th) {
            // $th é a exceção lançada pelo sistema - por norma, erro ocorre no servidor BD MySQL
            // Descomentar a próxima linha para verificar qual a informação que a exceção tem
            //dd($th, $th->errorInfo);
            return redirect()->route('config.index')
                ->with('alert-msg', 'Não foi possível apagar a sala"' . $oldID  . '". Erro: ' . $th->errorInfo[2])
                ->with('alert-type', 'danger');
        }
    }

    public function create_filme()
    {
        $filme = new Filme;

        $generos = Genero::pluck('nome', 'code');

        return view('administracao.filmes.create')->withFilme($filme)->withGeneros($generos);
    }

    public function store_filme(FilmePost $request)
    {

        $mytime = Carbon\Carbon::now();
        $format1 = 'Y';
        $ano = Carbon\Carbon::parse($mytime)->format($format1);


        $validated_data = $request->validated();

        $filme = new Filme;
        $filme->titulo = $validated_data['titulo'];
        $filme->genero_code = $validated_data['genero_code'];
        $filme->sumario = $validated_data['sumario'];
        $filme->trailer_url = $request->trailer_url;
        $filme->ano = $ano;
        if ($request->hasFile('cartaz')) {
            $path = 'storage/cartazes/';
            $filme->cartaz_url = $request->file('cartaz')->getClientOriginalName();
            $request->cartaz->move($path, $filme->cartaz_url);
        }

        $filme->save();

        return redirect()->route('config.index')->with('alert-msg', 'Filme Criado Com Sucesso')
            ->with('alert-type', 'success');
    }

    public function delete_filme(Filme $filme)
    {

        $oldID = $filme->id;

        try {
            $filme->delete();

            return redirect()->route('config.index')
                ->with('alert-msg', 'Filme foi apagado com sucesso!')
                ->with('alert-type', 'success');
        } catch (\Throwable $th) {
            // $th é a exceção lançada pelo sistema - por norma, erro ocorre no servidor BD MySQL
            // Descomentar a próxima linha para verificar qual a informação que a exceção tem
            //dd($th, $th->errorInfo);
            return redirect()->route('config.index')
                ->with('alert-msg', 'Não foi possível apagar o filme"' . $oldID  . '". Erro: ' . $th->errorInfo[2])
                ->with('alert-type', 'danger');
        }
    }

    public function edit_filme(Filme $filme)
    {
        $generos = Genero::pluck('nome', 'code');
        return view('administracao.filmes.edit')->withGeneros($generos)->withFilme($filme);
    }

    public function update_filme(FilmePost $request, Filme $filme)
    {
        $validated_data = $request->validated();

        $filme->titulo = $validated_data['titulo'];
        $filme->genero_code = $validated_data['genero_code'];
        $filme->sumario = $validated_data['sumario'];
        $filme->trailer_url = $request->trailer_url;

        if ($request->hasFile('cartaz')) {
            $path = 'storage/cartazes/';
            $filme->cartaz_url = $request->file('cartaz')->getClientOriginalName();
            $request->cartaz->move($path, $filme->cartaz_url);
        }

        $filme->save();

        return redirect()->route('config.index')->with('alert-msg', 'Filme alterado Com Sucesso')
            ->with('alert-type', 'success');
    }

    public function create_sessao($id)
    {

        $salas = Sala::pluck('nome', 'id');

        return view('administracao.sessoes.create')->withSalas($salas)->withId($id);
    }

    public function store_sessao(Request $request, $id)
    {
        $request->validate([
            'sessao.*.sala_id' => 'required',
            'sessao.*.data' => 'required',
            'sessao.*.horario_inicio' => 'required',
        ]);

        foreach ($request->sessao as $sessao) {
            for ($i = 0; $i < $sessao['dias']; $i++) {
                $data = Carbon\Carbon::createFromFormat('Y-m-d', $sessao['data']);

                if ($i > 0) {
                    $data->addDay();
                }

                $data = Carbon\Carbon::parse($data)->format('Y-m-d');

                $novaSessao = new Sessao;
                $novaSessao->filme_id = $id;
                $novaSessao->sala_id = $sessao['sala_id'];
                $novaSessao->data = $data;
                $novaSessao->horario_inicio = $sessao['horario_inicio'];

                $novaSessao->save();
            }
        }

        return redirect()->route('sessoes.filme', ['id' => $id])->with('alert-msg', 'Sessão Criada Com Sucesso')
            ->with('alert-type', 'success');
    }

    public function edit_sessao(Sessao $sessao, $id)
    {

        $salas = Sala::pluck('nome', 'id');

        return view('administracao.sessoes.edit')->withSalas($salas)->withId($id)->withSessao($sessao);
    }

    public function update_sessao(SessaoPost $request, $id)
    {
        $sessao = $request->validated();


        $novaSessao = new Sessao;
        $novaSessao->filme_id = $id;
        $novaSessao->sala_id = $sessao['sala_id'];
        $novaSessao->data = $sessao['data'];
        $novaSessao->horario_inicio = $sessao['horario_inicio'];

        $novaSessao->save();

        return redirect()->route('sessoes.filme', ['id' => $id])->with('alert-msg', 'Sessão alterada Com Sucesso')
            ->with('alert-type', 'success');
    }

    public function delete_sessao(Sessao $sessao)
    {

        $oldID = $sessao->id;
        $filmeID = $sessao->filme_id;

        $sessao = Sessao::find($oldID);

        try {
            $sessao->delete();

            return redirect()->route('sessoes.filme', ['id' => $filmeID])
                ->with('alert-msg', 'Sessão foi apagada com sucesso!')
                ->with('alert-type', 'success');
        } catch (\Throwable $th) {
            // $th é a exceção lançada pelo sistema - por norma, erro ocorre no servidor BD MySQL
            // Descomentar a próxima linha para verificar qual a informação que a exceção tem
            //dd($th, $th->errorInfo);
            return redirect()->route('sessoes.filme', ['id' => $filmeID])
                ->with('alert-msg', 'Não foi possível apagar a Sessão"' . $oldID  . '". Erro: ' . $th->errorInfo[2])
                ->with('alert-type', 'danger');
        }
    }
}
