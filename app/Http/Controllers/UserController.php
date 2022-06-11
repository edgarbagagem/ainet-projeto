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
        return view('administracao.index')->withUsers($users);
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
        return view('administracao.consultar')->withUser($user);
    }

    public function admin_editar(User $user)
    {
        return view('administracao.edit')->withUser($user);
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
        return view('administracao.create')->withUser($newUser);
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
}
