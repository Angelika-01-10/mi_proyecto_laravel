@extends('layouts.app')

@section('title', 'Crear Foro')

@section('content')
<h1 class="text-2xl font-bold mb-6">Crear un nuevo foro</h1>

{{-- Errores de validación --}}
@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('foros.store') }}" 
      method="POST" 
      enctype="multipart/form-data" {{-- 👈 Necesario para subir archivos --}}
      class="space-y-6">
    @csrf

    <div>
        <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
        <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}"
               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500 p-2"
               required>
    </div>

    <div>
        <label for="contenido" class="block text-sm font-medium text-gray-700">Contenido</label>
        <textarea name="contenido" id="contenido" rows="4"
                  class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500 p-2"
                  required>{{ old('contenido') }}</textarea>
    </div>

    {{-- Campo para imagen --}}
    <div>
        <label for="imagen" class="block text-sm font-medium text-gray-700">Imagen (opcional)</label>
        <input type="file" name="imagen" id="imagen"
               accept="image/*"
               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500 p-2">
    </div>

    <div class="flex justify-end space-x-4">
        <a href="{{ route('foros.index') }}"
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
            Cancelar
        </a>
        <button type="submit"
                class="px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition font-medium">
            Crear Foro
        </button>
    </div>
</form>
@endsection
