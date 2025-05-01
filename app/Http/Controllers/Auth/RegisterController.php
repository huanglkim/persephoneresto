<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\LoggingTrait;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Notifications\PendaftaranBerhasil;
use Illuminate\Support\Facades\Log;


class RegisterController extends Controller
{
    use RegistersUsers, LoggingTrait;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        $this->logAuthActivity('Pengguna baru terdaftar: ' . $data['name']);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    
        $notifikasiData = [
            'pesan' => 'Pendaftaran Anda berhasil!',
            'tautan' => '/profil',
        ];
    
        $user->notify(new PendaftaranBerhasil($notifikasiData));
    
        return $user;
    }
}