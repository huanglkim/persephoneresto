@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Notifikasi Anda</h1>
    @if (count($notifications) > 0)
        <ul>
            @foreach ($notifications as $notification)
                <li>
                    <strong>{{ $notification->created_at }}</strong><br>
                    {{ $notification->data['message'] }}
                    @if (isset($notification->data['link']))
                        <a href="{{ $notification->data['link'] }}">Lihat Detail</a>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <p>Tidak ada notifikasi.</p>
    @endif
</div>
@endsection