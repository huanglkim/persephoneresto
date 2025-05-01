<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Traits\LoggingTrait;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\User;

class LoginController extends Controller
{
    use AuthenticatesUsers, LoggingTrait;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle actions after user authenticated
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user)
    {
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda dinonaktifkan. Silakan hubungi administrator.'
            ]);
        }

        $this->logAuthActivity('Pengguna berhasil login: ' . $user->name);
        return redirect()->intended($this->redirectPath());
    }
    protected function sendFailedLoginResponse(Request $request)
    {
        // Cari user berdasarkan email
        $user = \App\User::where('email', $request->email)->first();

        if (!$user) {
            $message = 'Email tidak terdaftar.';
        } elseif (!Hash::check($request->password, $user->password)) {
            $message = 'Password salah.';
        } elseif (!$user->is_active) {
            $message = 'Akun Anda dinonaktifkan. Silakan hubungi administrator.';
        } else {
            $message = 'Login gagal. Silakan cek kembali email dan password Anda.';
        }

        throw ValidationException::withMessages([
            'email' => [$message],
        ]);
    }
}
