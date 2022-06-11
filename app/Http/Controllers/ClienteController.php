<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\User;

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
}
