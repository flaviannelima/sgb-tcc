<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    public function resetPassword($user, $password)
    {
        

        if(isset($user->ativo) && !$user->ativo){
            return redirect()->back()->withErrors(['ativo' => 'Usuário desativado.']);
        } else {
            $user->forceFill([
                'password' => bcrypt($password),
                'remember_token' => Str::random(60),
            ])->save();
            $this->guard()->login($user);
        }
    }
}
