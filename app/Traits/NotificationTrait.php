<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\User; 

trait NotificationTrait
{
    public function kirimEmailNotifikasi($user, $subjek, $pesan)
    {
        Mail::raw($pesan, function ($mail) use ($user, $subjek) {
            $mail->to($user->email)->subject($subjek);
        });
    }

    public function simpanNotifikasiDatabase($user, $data)
    {
        $user->notify(new \Illuminate\Notifications\Messages\DatabaseMessage($data));
    }
}