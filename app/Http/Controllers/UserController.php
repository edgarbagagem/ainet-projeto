<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Cliente;

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
            $path = $request['foto']->store('public/fotos');
            $valueForUserTable['foto_url'] = basename($path);
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
