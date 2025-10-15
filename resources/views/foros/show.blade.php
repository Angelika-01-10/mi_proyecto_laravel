@extends('layouts.app')

@section('title', $foro->titulo)

@section('content')
<h1 class="text-2xl font-bold mb-4">{{ $foro->titulo }}</h1>
<p>{{ $foro->contenido }}</p>

<hr class="my-4">

<h2 class="text-xl font-semibold mb-2">Comentarios</h2>
<ul class="space-y-3">
    @foreach($foro->comentarios as $comentario)
        <li class="border p-2 rounded">
            <strong>{{ $comentario->user->name }}:</strong>
            {{ $comentario->contenido }}
        </li>
    @endforeach
</ul>

@auth
    @if(Auth::user()->hasRole(['paciente', 'dermatologo', 'administrador']))
        <form method="POST" action="{{ route('foros.comentar', $foro->id) }}" class="mt-4">
            @csrf
            <textarea name="contenido" rows="3" class="w-full border rounded p-2" placeholder="Escribe tu comentario..."></textarea>
            <button class="mt-2 bg-blue-600 text-white px-4 py-2 rounded">Comentar</button>
        </form>
    @endif
@endauth
@endsection
