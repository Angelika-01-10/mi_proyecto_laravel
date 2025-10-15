{{-- resources/views/comentarios/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Editar Comentario')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Editar Comentario</h1>

    <form action="{{ route('comentarios.update', $comentario->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700">Contenido</label>
            <textarea name="contenido" class="w-full border p-2 rounded" required>{{ $comentario->contenido }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Imagen (opcional)</label>
            <input type="file" name="imagen" class="w-full">
            @if($comentario->imagen)
                <img src="{{ asset('storage/'.$comentario->imagen) }}" class="mt-2 w-32 h-32 object-cover">
            @endif
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Actualizar</button>
    </form>
</div>
@endsection
