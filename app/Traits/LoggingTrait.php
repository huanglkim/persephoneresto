<?php

namespace App\Traits;
use Illuminate\Support\Facades\Log;

trait LoggingTrait
{
    public function logAuthActivity($message)
    {
        Log::info('Aktivitas Autentikasi: ' . $message);
    }
}