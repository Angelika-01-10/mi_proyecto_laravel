@extends('layouts.app')

@section('title', 'Editar Videoconferencia')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">✏️ Editar Videoconferencia</h1>
        <p class="text-gray-600">Modifica los detalles de tu próxima sesión virtual</p>
    </div>

    <!-- Errores de validación -->
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
            <div class="flex">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 mb-2">Por favor corrige los siguientes errores:</h3>
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-start">
                                <span class="text-red-400 mr-2">•</span>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Formulario -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-8">
            <form action="{{ route('videoconferencias.update', $videoconferencia->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Título -->
                <div class="space-y-2">
                    <label for="titulo" class="block text-sm font-semibold text-gray-700">
                        📝 Título de la conferencia
                    </label>
                    <input type="text" 
                           name="titulo" 
                           id="titulo" 
                           value="{{ old('titulo', $videoconferencia->titulo) }}"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none"
                           required>
                </div>

                <!-- Descripción -->
                <div class="space-y-2">
                    <label for="descripcion" class="block text-sm font-semibold text-gray-700">
                        📄 Descripción (opcional)
                    </label>
                    <textarea name="descripcion" 
                              id="descripcion" 
                              rows="4"
                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none resize-none">{{ old('descripcion', $videoconferencia->descripcion) }}</textarea>
                </div>

                <!-- Fecha y hora -->
                <div class="space-y-2">
                    <label for="fecha" class="block text-sm font-semibold text-gray-700">
                        📅 Fecha y hora
                    </label>
                    <input type="datetime-local" 
                           name="fecha" 
                           id="fecha" 
                           value="{{ old('fecha', \Carbon\Carbon::parse($videoconferencia->fecha)->format('Y-m-d\TH:i')) }}"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none"
                           required>
                </div>

                <!-- Link de videoconferencia -->
                <div class="space-y-2">
                    <label for="link" class="block text-sm font-semibold text-gray-700">
                        🔗 Link de la videoconferencia
                    </label>
                    <input type="url" 
                           name="link" 
                           id="link" 
                           value="{{ old('link', $videoconferencia->link) }}"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none"
                           required>
                </div>

                <!-- Imagen -->
                <div class="space-y-2">
                    <label for="imagen" class="block text-sm font-semibold text-gray-700">
                        🖼️ Imagen promocional (opcional)
                    </label>
                    @if($videoconferencia->imagen)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $videoconferencia->imagen) }}" 
                                 alt="Imagen actual" 
                                 class="w-48 h-36 object-cover rounded border">
                        </div>
                    @endif
                    <input type="file" 
                           name="imagen" 
                           id="imagen"
                           accept="image/*"
                           class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-1">Dejar en blanco para no cambiar la imagen. Formatos admitidos: JPG, PNG, GIF (máx. 2MB)</p>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-100">
                    <a href="{{ route('videoconferencias.index') }}"
                       class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium">
                        ✖️ Cancelar
                    </a>
                    <button type="submit"
                            class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transform hover:scale-105 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl">
                        💾 Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer info -->
    <div class="text-center mt-8">
        <p class="text-sm text-gray-500">
            💡 Tip: Una vez guardados los cambios, los pacientes verán la información actualizada.
        </p>
    </div>
</div>
@endsection
