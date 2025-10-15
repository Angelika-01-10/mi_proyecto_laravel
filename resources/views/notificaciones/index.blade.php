@extends('layouts.app')

@section('title', 'Notificaciones')

@section('content')
<style>
    .notificaciones-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 1rem;
    }

    .notificacion-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        margin-bottom: 1rem;
        padding: 1rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        transition: transform 0.2s;
    }
    .notificacion-card:hover {
        transform: translateY(-2px);
    }

    .notificacion-title {
        font-weight: bold;
        color: #111827;
        margin-bottom: 0.25rem;
    }

    .notificacion-time {
        font-size: 0.8rem;
        color: #6b7280;
    }

    .btn-marcar {
        background: linear-gradient(135deg, #fc6c85, #ec4899);
        color: #fff;
        padding: 0.4rem 1rem;
        border-radius: 9999px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: 0.2s;
        margin-bottom: 1rem;
    }
    .btn-marcar:hover {
        background: #db2777;
    }
</style>

<div class="notificaciones-container">
    <h1 class="text-2xl font-bold mb-4">🔔 Mis Notificaciones</h1>

    @if($notificaciones->isEmpty())
        <p class="text-gray-500 text-center">No tienes notificaciones.</p>
    @else
        <form action="{{ route('notificaciones.marcarLeidas') }}" method="POST">
            @csrf
            <button type="submit" class="btn-marcar">✅ Marcar todas como leídas</button>
        </form>

        @foreach($notificaciones as $notificacion)
            <div class="notificacion-card {{ $notificacion->read_at ? 'opacity-60' : '' }}">
                <p class="notificacion-title">
                    {{ $notificacion->data['titulo'] ?? 'Notificación' }}
                </p>
                <p>{{ $notificacion->data['mensaje'] ?? '' }}</p>
                <p class="notificacion-time">
                    {{ $notificacion->created_at->diffForHumans() }}
                </p>
            </div>
        @endforeach
    @endif
</div>
@endsection
