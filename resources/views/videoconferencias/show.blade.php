@extends('layouts.app')

@section('title', $videoconferencia->titulo)

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">{{ $videoconferencia->titulo }}</h1>

    {{-- Imagen --}}
    @if($videoconferencia->imagen)
        <div class="mb-4">
            <img src="{{ asset('storage/' . $videoconferencia->imagen) }}" 
                 alt="{{ $videoconferencia->titulo }}" 
                 class="w-full max-w-md rounded shadow">
        </div>
    @endif

    <p class="mb-2"><strong>Fecha:</strong> {{ $videoconferencia->fecha }}</p>
    <p class="mb-2">
        <strong>Link de la reunión:</strong> 
        <a href="{{ $videoconferencia->link }}" target="_blank" class="text-blue-600 underline">
            {{ $videoconferencia->link }}
        </a>
    </p>
    <p class="mb-4"><strong>Creador:</strong> {{ $videoconferencia->dermatologo->name }}</p>

    {{-- Botón para inscribirse (solo pacientes) --}}
    @auth
        @if(Auth::user()->hasRole('paciente'))
            <form action="{{ route('videoconferencias.inscribirse', $videoconferencia) }}" method="POST" class="mt-4">
                @csrf
                <button type="submit"
                        class="px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition font-medium">
                    Inscribirse
                </button>
            </form>
        @endif
    @endauth

    <div class="mt-6">
        <a href="{{ route('videoconferencias.index') }}" class="text-blue-600 hover:underline">
            ← Volver a la lista de videoconferencias
        </a>
    </div>
</div>
@endsection
