<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    public function index()
    {
        return view('email_index')->with('pageTitle', 'E-Mail');
    }

    public function validatePasswordRequest(Request $request){

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
}

