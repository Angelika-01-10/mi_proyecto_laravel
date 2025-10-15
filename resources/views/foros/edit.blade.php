@extends('layouts.app')

@section('title', 'Editar Foro')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold mb-4">✏️ Editar Foro</h1>

    <form action="{{ route('foros.update', $foro->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Título -->
        <div class="mb-4">
            <label class="block text-sm font-medium">Título</label>
            <input type="text" name="titulo" value="{{ old('titulo', $foro->titulo) }}"
                class="w-full border rounded p-2" required>
        </div>

        <!-- Contenido -->
        <div class="mb-4">
            <label class="block text-sm font-medium">Contenido</label>
            <textarea name="contenido" rows="4" class="w-full border rounded p-2" required>{{ old('contenido', $foro->contenido) }}</textarea>
        </div>

        <!-- Imagen -->
        <div class="mb-4">
            <label class="block text-sm font-medium">Imagen</label>
            <input type="file" name="imagen" class="w-full border rounded p-2">

            @if($foro->imagen)
                <p class="mt-2">Imagen actual:</p>
                <img src="{{ asset('storage/'.$foro->imagen) }}" alt="Imagen foro" class="w-40 rounded">
            @endif
        </div>

        <!-- Botón -->
        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded">
            💾 Guardar cambios
        </button>
    </form>
</div>
@endsection
