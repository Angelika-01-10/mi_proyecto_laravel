@extends('layouts.app')

@section('title', isset($historia) ? 'Editar Historia de Piel' : 'Registrar Historia de Piel')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<div class="max-w-2xl mx-auto mt-10">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            {{ isset($historia) ? '✏️ Editar Historia de Piel' : '🩺 Crear Historia de Piel' }}
        </h1>
        <p class="text-gray-600">
            Describe tus síntomas y adjunta tus archivos para recibir orientación dermatológica
        </p>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-red-800 mb-2">Corrige los siguientes errores:</h3>
            <ul class="text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="flex items-start"><span class="text-red-400 mr-2">•</span>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-8">
            <form action="{{ isset($historia) ? route('historias.update', $historia->id) : route('historias.store') }}"
                  method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($historia))
                    @method('PUT')
                @endif

                <!-- Foto de perfil -->
                <div class="space-y-2">
                    <label for="foto_perfil" class="block text-sm font-semibold text-gray-700">
                        📷 Foto de perfil o zona afectada (opcional)
                    </label>
                    <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*"
                        class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 outline-none">
                    @if(isset($historia) && $historia->foto_perfil)
                        <p class="text-xs text-gray-500">Foto actual: 
                            <a href="{{ asset('storage/'.$historia->foto_perfil) }}" target="_blank" class="underline text-blue-600">Ver</a>
                        </p>
                    @endif
                </div>

                <!-- Documento médico -->
                <div class="space-y-2">
                    <label for="documento_medico" class="block text-sm font-semibold text-gray-700">
                        📑 Documento médico (opcional)
                    </label>
                    <input type="file" name="documento_medico" id="documento_medico" accept=".pdf,.docx,.jpg,.png"
                        class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 outline-none">
                    @if(isset($historia) && $historia->documento_medico)
                        <p class="text-xs text-gray-500">Documento actual: 
                            <a href="{{ asset('storage/'.$historia->documento_medico) }}" target="_blank" class="underline text-blue-600">Ver</a>
                        </p>
                    @endif
                </div>

                <!-- Descripción de síntomas -->
                <div class="space-y-2">
                    <label for="descripcion_sintomas" class="block text-sm font-semibold text-gray-700">
                        💬 Describe tus síntomas
                    </label>
                    <textarea name="descripcion_sintomas" id="descripcion_sintomas" rows="5"
                        placeholder="Describe qué sientes, desde cuándo y si has usado algún tratamiento..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 outline-none resize-none"
                        required>{{ old('descripcion_sintomas', $historia->descripcion_sintomas ?? '') }}</textarea>
                </div>

                <!-- Dermatólogo (opcional) -->
                @if(isset($dermatologos) && count($dermatologos) > 0)
                <div class="space-y-2">
                    <label for="dermatologo_id" class="block text-sm font-semibold text-gray-700">
                        👩‍⚕️ Selecciona un dermatólogo (opcional)
                    </label>
                    <select name="dermatologo_id" id="dermatologo_id"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 outline-none">
                        <option value="">-- No asignar aún --</option>
                        @foreach($dermatologos as $derma)
                            <option value="{{ $derma->id }}"
                                @if(old('dermatologo_id', $historia->dermatologo_id ?? '') == $derma->id) selected @endif>
                                {{ $derma->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Botones -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-100">
                    <a href="{{ route('historias.index') }}"
                        class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium">
                        ✖️ Cancelar
                    </a>
                    <button type="submit"
                        class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transform hover:scale-105 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl">
                        ✅ {{ isset($historia) ? 'Actualizar Historia' : 'Guardar Historia' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="text-center mt-8">
        <p class="text-sm text-gray-500">
            🌿 Esta información será revisada con confidencialidad por un dermatólogo autorizado.
        </p>
    </div>
</div>
@endsection
