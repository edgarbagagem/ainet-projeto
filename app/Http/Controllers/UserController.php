<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Cliente;
use App\Http\Requests\UserPost;
use Carbon;
use App\Models\Sessao;
use App\Models\Bilhete;
use App\Models\Filme;
use App\Models\Sala;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $user = \Auth::user();
        $cliente = Cliente::where('id', '=', $user->id)->first();
        return view('users.index', ['user' => $user],  ['cliente' => $cliente]);
    }

    public function editPerfil(Request $request)
    {
        $user = \Auth::user();
        return view('users.edit', ['user' => $user]);
    }


    public function updatePerfil(Request $request)
    {
        $user = \Auth::user();
        $cliente = Cliente::where('id', '=', $user->id)->first();
        $valueForUserTable['name'] = $request['nome'];
        $valueForUserTable['email'] = $request['email'];
        $valueForClientesTable['nif'] = $request['nif'];
        $valueForClientesTable['tipo_pagamento'] = $request['tipoPagamento'];
        if ($request->hasFile('foto')) {
            $path = 'storage/fotos/';
            $valueForUserTable['foto_url'] = $request->file('foto')->getClientOriginalName();
            $request->foto->move($path, $valueForUserTable['foto_url']);
        }
        User::where('id', \Auth::user()->id)->update($valueForUserTable);
        Cliente::where('id', \Auth::user()->id)->update($valueForClientesTable);
        $user = User::where('id', \Auth::user()->id)->get();
        $user = $user->first();

        //return view('users.index', ['user' => $user], ['cliente' => $cliente]);
        return redirect()->route('index.user');
    }

    public function editPassword(Request $request)
    {
        $user = \Auth::user();
        return view('users.editPassword', ['user' => $user]);
    }

    public function updatePassword(Request $request)
    {
        $user = \Auth::user();
        if (!Hash::check($request['old_password'], $user->password)) {
            return back();
        }
        $value['password'] = Hash::make($request['new_password']);
        User::where('id', \Auth::user()->id)->update($value);

        return view('users.index', ['user' => $user]);
    }

    public function validatePasswordRequest(Request $request)
    {

        $user = DB::table('users')->where('email', '=', $request->email)
            ->first();
        //Check if the user exists
        if (count($user) < 1) {
            return redirect()->back()->withErrors(['email' => trans('User does not exist')]);
        }

        //Create Password Reset Token
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => str_random(60),
            'created_at' => Carbon::now()
        ]);
        //Get the token just created above
        $tokenData = DB::table('password_resets')
            ->where('email', $request->email)->first();

        if ($this->send_email_with_notification($request->email, $tokenData->token)) {
            return redirect()->back()->with('status', trans('A reset link has been sent to your email address.'));
        } else {
            return redirect()->back()->withErrors(['error' => trans('A Network Error occurred. Please try again.')]);
        }
    }

    public function send_email_with_notification($email, $token)
    {
        /*
        // SEND EMAIL WITH USER MODEL
        $activeUser = \Auth::user();
        // Send to user:
        $user = User::findOrFail($activeUser);
        $user->notify(new ResetPassword());
        return redirect()->route('email.home')
            ->with('alert-type', 'success')
            ->with('alert-msg', 'E-Mail sent with success (using Notifications)');`
            */
        $user = DB::table('users')->where('email', $email)->select('firstname', 'email')->first();

        $link = config('base_url') . '/password/reset' . $token . '?email=' . urlencode($user->email);
    }

    public function admin(Request $request)
    {

        $substring = $request->substring ?? '';

        $users = User::query();

        $users = $users->where(function ($query) {
            $query->where('tipo', '=', 'A')
                ->orWhere('tipo', '=', 'F');
        });

        $users = $users->where('id', '!=', Auth()->user()->id);

        if ($substring) {
            $users = $users->where(function ($query) use ($substring) {
                $query->where('users.name', 'LIKE', "%{$substring}%")
                    ->orWhere('users.id', 'like', "%{$substring}%");
            });
        }

        $users = $users->paginate(10);
        return view('administracao.users.index')->withUsers($users);
    }

    public function admin_delete(User $user)
    {
        $oldID = $user->id;

        try {
            $user->delete();

            return redirect()->route('users.admin')
                ->with('alert-msg', 'User"' . $oldID . '" foi apagado com sucesso!')
                ->with('alert-type', 'success');
        } catch (\Throwable $th) {
            // $th é a exceção lançada pelo sistema - por norma, erro ocorre no servidor BD MySQL
            // Descomentar a próxima linha para verificar qual a informação que a exceção tem
            //dd($th, $th->errorInfo);
            return redirect()->route('users.admin')
                ->with('alert-msg', 'Não foi possível apagar o User"' . $oldID   . '". Erro: ' . $th->errorInfo[2])
                ->with('alert-type', 'danger');
        }
    }

    public function admin_blockunblock(User $user)
    {

        try {
            if ($user->bloqueado == 1) {
                $user->bloqueado = 0;
            } else {
                $user->bloqueado = 1;
            }

            $user->save();


            return redirect()->route('users.admin')
                ->with('alert-msg', 'user "' . $user->id . '" foi bloqueado/desbloqueado com sucesso!')
                ->with('alert-type', 'success');
        } catch (\Throwable $th) {
            // $th é a exceção lançada pelo sistema - por norma, erro ocorre no servidor BD MySQL
            // Descomentar a próxima linha para verificar qual a informação que a exceção tem
            //dd($th, $th->errorInfo);
            return redirect()->route('users.admin')
                ->with('alert-msg', 'Não foi possível bloquear/desbloquear o user "' . $user->id  . '". Erro: ' . $th->errorInfo[2])
                ->with('alert-type', 'danger');
        }
    }

    public function admin_consultar(User $user)
    {
        return view('administracao.users.consultar')->withUser($user);
    }

    public function admin_editar(User $user)
    {
        return view('administracao.users.edit')->withUser($user);
    }

    public function admin_updateUser(UserPost $request, User $user)
    {
        $validated_data = $request->validated();
        $user->name = $validated_data['name'];
        $user->email = $validated_data['email'];
        $user->tipo = $request->tipo;
        if ($request->hasFile('foto')) {
            $path = 'storage/fotos/';
            $user->foto_url = $request->file('foto')->getClientOriginalName();
            $request->foto->move($path, $user->foto_url);
        }
        $user->save();
        return redirect()->route('users.admin')
            ->with('alert-msg', 'user "' . $user->id . '" foi alterado com sucesso!')
            ->with('alert-type', 'success');
    }

    public function admin_create()
    {
        $newUser = new User;
        return view('administracao.users.create')->withUser($newUser);
    }

    public function admin_store(UserPost $request)
    {
        $validated_data = $request->validated();
        $newUser = new User;
        $newUser->name = $validated_data['name'];
        $newUser->password = Hash::make('123');
        $newUser->email = $validated_data['email'];
        $newUser->tipo = $request->tipo;
        if ($request->hasFile('foto')) {
            $path = 'storage/fotos/';
            $newUser->foto_url = $request->file('foto')->getClientOriginalName();
            $request->foto->move($path, $newUser->foto_url);
        }
        $newUser->save();
        return redirect()->route('users.admin')
            ->with('alert-msg', 'user criado  com sucesso!')
            ->with('alert-type', 'success');
    }

    public function sessionControl(Request $request){

        $filme = $request->filme ?? '';
        $sala = $request->sala ?? '';

        $mytime = Carbon\Carbon::now()->subminute(5);
        $format1 = 'Y-m-d';
        $format2 = 'H:i:s';
        $data = Carbon\Carbon::parse($mytime)->format($format1);
        $time = Carbon\Carbon::parse($mytime)->format($format2);
    
        //sessoes todas
        $sessoes = Sessao::query();

        $sessoes = $sessoes->select('sessoes.id AS id', 'filmes.titulo AS titulo', 'sessoes.data', 'sessoes.horario_inicio', 'salas.nome AS sala', 'salas.id AS sala_id')
            ->join('filmes', 'filmes.id', '=', 'sessoes.filme_id')
            ->join('salas', 'salas.id', '=', 'sessoes.sala_id')
            ->where(function ($query) use ($data, $time) {
                $query->where('sessoes.data', '>', $data)
                    ->orWhere(function ($query1) use ($data, $time) {
                        $query1->where('sessoes.data', '=', $data)
                            ->where('sessoes.horario_inicio', '>=', $time);
                    });
            });
            



            //filtrar filme
        
            $filmes = Filme::query();

            $filmes = $filmes->select('filmes.titulo AS titulo')
                ->join('sessoes', 'filmes.id', '=', 'sessoes.filme_id')
                ->where(function ($query) use ($data, $time) {
                    $query->where('sessoes.data', '>', $data)
                        ->orWhere(function ($query1) use ($data, $time) {
                            $query1->where('sessoes.data', '=', $data)
                                ->where('sessoes.horario_inicio', '>=', $time);
                        });
                })
                ->groupBy('filmes.titulo');
                

            $filmes =  Filme::whereIn('titulo',$filmes)->pluck('titulo', 'id');
            
              if($filme){
            $sessoes = $sessoes->where('filmes.id', '=', $filme);                 
            }

            /*
            if($filme and $sala)
                select as sessoes daquele filme como se ja fazia, e com aquela sala
            */



            //Filme selected
            /*
            $salasFilme = Sala::query();
                
            $salasFilme = $salasFilme->select('sala.nome')
                ->join('sessoes', 'sala.id', '=', 'sessoes.sala_id')
                ->join('filmes', 'sessoes.filmes_id', '=', 'filmes_id')
                ->where('filmes.titulo', '=', $filme)
                ->groupBy('sala.id')
                ->get();
            
            $salasFilme = Sala::whereIn('nome', $salasFilme)->pluck('nome', 'id');

            if($sala){
                $salasFilme = $salasFilme->where('nome', $salasFilme);
            }*/
            $sessoes = $sessoes->paginate(10);

        return view('controloSessao.index')->withSessoes($sessoes)->withFilmes($filmes)->withSelectedFilme($filme);//   ->withSalaFilme($salasFilme)->withSala($sala);
    }

    public function controlledSession($id){
        
        $sessoes = Sessao::query();
        $sessoes = $sessoes->select('sessoes.id AS id', 'filmes.titulo AS titulo', 'sessoes.data', 'sessoes.horario_inicio', 'salas.nome AS sala', 'salas.id AS sala_id')
        ->join('filmes', 'filmes.id', '=', 'sessoes.filme_id')
        ->join('salas', 'salas.id', '=', 'sessoes.sala_id')
        ->where('sessoes.id', '=', $id)->first();
      

        return view('controloSessao.validate')->withSessao($sessoes);
           


    }

    public function validateTickets(Request $request, Sessao $sessao){
    
        $id = $request['id'];

        $bilhete = Bilhete::query();
        $bilhete = $bilhete->where('id', '=', $id)->first();
        $bilheteCount = Bilhete::query();
        $bilheteCount = $bilheteCount->where('id', '=', $id)->count();
        $str = "não usado";

        if($bilheteCount < 1){
            return redirect()->route('controloSessao.sessao', ['id' => $sessao->id])
            ->with('alert-msg', 'Bilhete "' . $id . '" não existe!')
            ->with('alert-type', 'danger');
        }else{

        if($bilhete->sessao_id != $sessao->id){
            return redirect()->route('controloSessao.sessao', ['id' => $sessao->id])
            ->with('alert-msg', 'Bilhete "' . $id . '" não é desta sessão!')
            ->with('alert-type', 'danger');
        }else{


        if (strcmp($bilhete->estado, $str) == 0) {
            $bilhete->estado = "usado";
            $cliente = User::where('id', '=', $bilhete->cliente_id)->first();
            $bilhete->save();
            
            return redirect()->route('controloSessao.show', ['id' => $sessao->id, 'bilhete_id' => $bilhete->id, 'cliente_id' => $cliente->id])
            ->with('alert-msg', 'Bilhete "' . $id . '" foi validado com sucesso!')
            ->with('alert-type', 'success');
        }else{
            return redirect()->route('controloSessao.sessao', ['id' => $sessao->id])
            ->with('alert-msg', 'Bilhete "' . $id . '" já foi usado!')
            ->with('alert-type', 'danger');
        }
    }
    }

}

public function showTicket($id, $bilhete_id, $cliente_id){
        
    $sessoes = Sessao::query();
    $sessoes = $sessoes->select('sessoes.id AS id', 'filmes.titulo AS titulo', 'sessoes.data', 'sessoes.horario_inicio', 'salas.nome AS sala', 'salas.id AS sala_id')
    ->join('filmes', 'filmes.id', '=', 'sessoes.filme_id')
    ->join('salas', 'salas.id', '=', 'sessoes.sala_id')
    ->where('sessoes.id', '=', $id)->first();
    $bilhete = Bilhete::query();
    $bilhete = $bilhete->select('id', 'recibo_id', 'cliente_id', 'sessao_id', 'lugar_id', 'preco_sem_iva')
    ->where('id', '=', $bilhete_id)->first();

    $cliente = User::query();
    $cliente = $cliente->select('name', 'foto_url')->where('id', '=', $bilhete->cliente_id)->first();

    return view('controloSessao.show')->withSessao($sessoes)->withBilhete($bilhete)->withCliente($cliente);
       


}


}

    /*
public function resetPassword(Request $request)
{
    //Validate input
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
        'password' => 'required|confirmed',
        'token' => 'required' ]);

    //check if payload is valid before moving on
    if ($validator->fails()) {
        return redirect()->back()->withErrors(['email' => 'Please complete the form']);
    }

    $password = $request->password;
// Validate the token
    $tokenData = DB::table('password_resets')
    ->where('token', $request->token)->first();
// Redirect the user back to the password reset request form if the token is invalid
    if (!$tokenData) return view('auth.passwords.email');

    $user = User::where('email', $tokenData->email)->first();
// Redirect the user back if the email is invalid
    if (!$user) return redirect()->back()->withErrors(['email' => 'Email not found']);
//Hash and update the new password
    $user->password = \Hash::make($password);
    $user->update(); //or $user->save();

    //login the user immediately they change password successfully
    Auth::login($user);

    //Delete the token
    DB::table('password_resets')->where('email', $user->email)
    ->delete();

    //Send Email Reset Success Email
    if ($this->sendSuccessEmail($tokenData->email)) {
        return view('index');
    } else {
        return redirect()->back()->withErrors(['email' => trans('A Network Error occurred. Please try again.')]);
    }


}
}
*/

