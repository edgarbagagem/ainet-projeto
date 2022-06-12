<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sala;

class ConfigurationController extends Controller
{

    public $alphabet = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

    public function index()
    {
        $configuracao = DB::table('configuracao')->first();

        $salas = Sala::query();
        $salas = $salas->paginate(10);

        foreach ($salas as $sala) {
            $totalLugares = DB::table('lugares')->where('sala_id', '=', $sala->id)->count();
            $sala->lugares = $totalLugares;
        }
        return view('administracao.negocio')->withConfiguracao($configuracao)
            ->withSalas($salas);
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
                ->with('alert-msg', 'Não foi possível apagar a sala"' . $sala->id   . '". Erro: ' . $th->errorInfo[2])
                ->with('alert-type', 'danger');
        }
    }
}
