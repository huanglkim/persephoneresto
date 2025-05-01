<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

class TestEmailController extends Controller
{
    public function kirimEmail()
    {
        Mail::raw('Ini adalah email teks biasa.', function ($message) {
            $message->to('penerima@example.com')->subject('Email Teks Biasa');
        });

        return 'Email berhasil dikirim!';
    }
}