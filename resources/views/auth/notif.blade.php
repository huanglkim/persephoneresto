@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Notifikasi Anda</h1>
    @if (count($notifications) > 0)
        <ul>
            @foreach ($notifications as $notif)
                <li>
                    {{ $notif->data['pesan'] }}
                    @if (isset($notif->data['tautan']))
                        <a href="{{ route('profile.show') }}">Lihat Detail</a>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <p>Tidak ada notifikasi.</p>
    @endif
</div>
@endsection