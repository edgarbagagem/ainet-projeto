<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Recibo;
use App\Models\Bilhete;
use App\Models\Filme;
use App\Models\Sala;
use App\Models\Sessao;
use Illuminate\Support\Facades\DB;
use PDF;
use Carbon;
use QrCode;

class ClienteController extends Controller
{

    public function index(Request $request)
    {
        $substring = $request->substring ?? '';


        $clientes = Cliente::query();

        $clientes = $clientes->select('clientes.id AS id', 'users.name AS nome', 'users.email AS email', 'users.bloqueado AS bloqueado', 'users.foto_url AS foto_url')
            ->join('users', 'users.id', '=', 'clientes.id');


        if ($substring) {
            $clientes = $clientes->where(function ($query) use ($substring) {
                $query->where('users.name', 'LIKE', "%{$substring}%")
                    ->orWhere('clientes.id', 'like', "%{$substring}%");
            });
        }

        $clientes = $clientes->paginate(10);
        return view('clientes.index')->withClientes($clientes);
    }

    public function delete(Cliente $cliente)
    {
        $oldID = $cliente->id;

        try {
            $cliente->delete();
            User::destroy($oldID);

            return redirect()->route('clientes.index')
                ->with('alert-msg', 'Cliente "' . $oldID . '" foi apagado com sucesso!')
                ->with('alert-type', 'success');
        } catch (\Throwable $th) {
            // $th é a exceção lançada pelo sistema - por norma, erro ocorre no servidor BD MySQL
            // Descomentar a próxima linha para verificar qual a informação que a exceção tem
            //dd($th, $th->errorInfo);
            return redirect()->route('clientes.index')
                ->with('alert-msg', 'Não foi possível apagar o Cliente "' . $oldID  . '". Erro: ' . $th->errorInfo[2])
                ->with('alert-type', 'danger');
        }
    }

    public function blockunblock(Cliente $cliente)
    {
        $user1 = User::find($cliente->id);

        try {
            if ($user1->bloqueado == 1) {
                $user1->bloqueado = 0;
            } else {
                $user1->bloqueado = 1;
            }

            $user1->save();

            return redirect()->route('clientes.index')
                ->with('alert-msg', 'Cliente "' . $user1->id . '" foi bloqueado/desbloqueado com sucesso!')
                ->with('alert-type', 'success');
        } catch (\Throwable $th) {
            // $th é a exceção lançada pelo sistema - por norma, erro ocorre no servidor BD MySQL
            // Descomentar a próxima linha para verificar qual a informação que a exceção tem
            //dd($th, $th->errorInfo);
            return redirect()->route('clientes.index')
                ->with('alert-msg', 'Não foi possível bloquear/desbloquear o Cliente "' . $cliente->id  . '". Erro: ' . $th->errorInfo[2])
                ->with('alert-type', 'danger');
        }
    }

    public function cliente_recibos(User $user)
    {

        $recibos = Recibo::where('cliente_id', '=', $user->id)->paginate(10);

        return view('cliente.recibos')->withRecibos($recibos);
    }

    public function cliente_recibo(User $user, Recibo $recibo)
    {
        $bilhetes = Bilhete::where('recibo_id', '=', $recibo->id)->get();
        foreach ($bilhetes as $bilhete) {
            $sessao = Sessao::find($bilhete->sessao_id);
            $filme = Filme::find($sessao->filme_id);
            $sala = Sala::find($sessao->sala_id);
            $lugar = DB::table('lugares')->where('id', '=', $bilhete->lugar_id)->first();
            $bilhete->filme = $filme->titulo;
            $bilhete->sala = $sala->nome;
            $bilhete->data = $sessao->data;
            $bilhete->horario = $sessao->horario_inicio;
            $bilhete->lugar = $lugar->fila . $lugar->posicao;
        }

        return view('cliente.recibo')->withRecibo($recibo)->withBilhetes($bilhetes);
    }

    public function cliente_recibo_pdf(User $user, Recibo $recibo)
    {
        $bilhetes = Bilhete::where('recibo_id', '=', $recibo->id)->get();
        foreach ($bilhetes as $bilhete) {
            $sessao = Sessao::find($bilhete->sessao_id);
            $filme = Filme::find($sessao->filme_id);
            $sala = Sala::find($sessao->sala_id);
            $lugar = DB::table('lugares')->where('id', '=', $bilhete->lugar_id)->first();
            $bilhete->filme = $filme->titulo;
            $bilhete->sala = $sala->nome;
            $bilhete->data = $sessao->data;
            $bilhete->horario = $sessao->horario_inicio;
            $bilhete->lugar = $lugar->fila . $lugar->posicao;
        }

        $data = array('recibo', 'bilhetes');

        $data['recibo'] = $recibo;
        $data['bilhetes'] = $bilhetes;

        $pdf = PDF::loadView('cliente.recibo', $data);

        return $pdf->download('recibo' . $recibo->id . '.pdf');
    }

    public function cliente_recibo_bilhetes(User $user, Recibo $recibo)
    {
        $bilhetes = Bilhete::where('recibo_id', '=', $recibo->id)->paginate(10);
        foreach ($bilhetes as $bilhete) {
            $sessao = Sessao::find($bilhete->sessao_id);
            $filme = Filme::find($sessao->filme_id);
            $sala = Sala::find($sessao->sala_id);
            $lugar = DB::table('lugares')->where('id', '=', $bilhete->lugar_id)->first();
            $bilhete->filme = $filme->titulo;
            $bilhete->sala = $sala->nome;
            $bilhete->data = $sessao->data;
            $bilhete->horario = $sessao->horario_inicio;
            $bilhete->lugar = $lugar->fila . $lugar->posicao;
        }
        return view('cliente.bilhetes')->withBilhetes($bilhetes);
    }



    public function cliente_bilhetes(User $user)
    {
        $mytime = Carbon\Carbon::now();
        $format1 = 'Y-m-d';
        $format2 = 'H:i:s';
        $data = Carbon\Carbon::parse($mytime)->format($format1);
        $time = Carbon\Carbon::parse($mytime)->format($format2);

        $bilhetes = Bilhete::select('bilhetes.id AS id', 'bilhetes.sessao_id AS sessao_id', 'bilhetes.lugar_id AS lugar_id')
            ->where('cliente_id', '=', Auth()->user()->id)
            ->join('sessoes', 'sessoes.id', '=', 'bilhetes.sessao_id')
            ->where(function ($query) use ($data, $time) {
                $query->where('sessoes.data', '>', $data)
                    ->orWhere(function ($query1) use ($data, $time) {
                        $query1->where('sessoes.data', '=', $data)
                            ->where('sessoes.horario_inicio', '>=', $time);
                    });
            })
            ->where('bilhetes.estado', '=', 'não usado')
            ->paginate(10);
        foreach ($bilhetes as $bilhete) {
            $sessao = Sessao::find($bilhete->sessao_id);
            $filme = Filme::find($sessao->filme_id);
            $sala = Sala::find($sessao->sala_id);
            $lugar = DB::table('lugares')->where('id', '=', $bilhete->lugar_id)->first();
            $bilhete->filme = $filme->titulo;
            $bilhete->sala = $sala->nome;
            $bilhete->data = $sessao->data;
            $bilhete->horario = $sessao->horario_inicio;
            $bilhete->lugar = $lugar->fila . $lugar->posicao;
        }

        return view('cliente.bilhetes')->withBilhetes($bilhetes);
    }

    public function cliente_bilhete(User $user, Bilhete $bilhete)
    {
        $bilhete = Bilhete::where('id', '=', $bilhete->id)->first();

        $sessao = Sessao::find($bilhete->sessao_id);
        $filme = Filme::find($sessao->filme_id);
        $sala = Sala::find($sessao->sala_id);
        $cliente = User::find($bilhete->cliente_id);
        $lugar = DB::table('lugares')->where('id', '=', $bilhete->lugar_id)->first();
        $bilhete->filme = $filme->titulo;
        $bilhete->sala = $sala->nome;
        $bilhete->data = $sessao->data;
        $bilhete->horario = $sessao->horario_inicio;
        $bilhete->lugar = $lugar->fila . $lugar->posicao;
        $bilhete->cliente = $cliente->name;
        $bilhete->foto_url = $cliente->foto_url;
        $bilhete->pdf = false;
        $bilhete->cliente_id = $cliente->id;

        return view('cliente.bilhete')->withBilhete($bilhete);
    }

    public function cliente_bilhete_pdf(User $user, Bilhete $bilhete)
    {
        $bilhete = Bilhete::where('id', '=', $bilhete->id)->first();

        $sessao = Sessao::find($bilhete->sessao_id);
        $filme = Filme::find($sessao->filme_id);
        $sala = Sala::find($sessao->sala_id);
        $cliente = User::find($bilhete->cliente_id);
        $lugar = DB::table('lugares')->where('id', '=', $bilhete->lugar_id)->first();
        $bilhete->filme = $filme->titulo;
        $bilhete->sala = $sala->nome;
        $bilhete->data = $sessao->data;
        $bilhete->horario = $sessao->horario_inicio;
        $bilhete->lugar = $lugar->fila . $lugar->posicao;
        $bilhete->cliente = $cliente->name;
        $bilhete->foto_url = $cliente->foto_url;
        $bilhete->pdf = true;
        $bilhete->cliente_id = $cliente->id;

        $bilhete->qrcode = base64_encode(QrCode::size(100)->generate('http://ainet-projeto.test/bilhetes/{{$bilhete->cliente_id}}/{{$bilhete->id}}'));

        $data = array('bilhete');

        $data['bilhete'] = $bilhete;

        $pdf = PDF::loadView('cliente.bilhete', $data);

        return $pdf->download('bilhete' . $bilhete->id . '.pdf');
    }
}
