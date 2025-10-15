@extends('layouts.app')

@section('title', 'Perfil del Dermatólogo')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-2xl p-8 mt-10 border-2 border-[#FC6C85]">
    <div class="flex flex-col md:flex-row gap-6 items-center">
        <img src="{{ asset('storage/' . $dermatologo->perfilDermatologo->foto_perfil) }}" 
             alt="Foto de {{ $dermatologo->name }}" 
             class="w-40 h-40 rounded-xl border-4 border-[#FC6C85] object-cover shadow-md">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $dermatologo->name }}</h1>
            <p class="text-gray-600">{{ $dermatologo->perfilDermatologo->titulo_profesional }}</p>
            <p class="text-gray-600">📞 {{ $dermatologo->perfilDermatologo->telefono }}</p>
            <p class="text-gray-600">🩺 {{ $dermatologo->perfilDermatologo->especialidad }}</p>
        </div>
    </div>

    <div class="mt-6">
        <h2 class="text-xl font-semibold mb-2 text-[#FC6C85]">Biografía Profesional</h2>
        <p class="text-gray-700 leading-relaxed">
            {{ $dermatologo->perfilDermatologo->biografia }}
        </p>
    </div>

    <div class="mt-6 grid grid-cols-2 gap-4 text-gray-700">
        <p><strong>Licencia:</strong> {{ $dermatologo->perfilDermatologo->numero_licencia }}</p>
        <p><strong>Experiencia:</strong> {{ $dermatologo->perfilDermatologo->años_experiencia }} años</p>
    </div>

    @if($dermatologo->perfilDermatologo->curriculum)
    <div class="mt-6">
        <a href="{{ asset('storage/' . $dermatologo->perfilDermatologo->curriculum) }}" 
           target="_blank" 
           class="px-4 py-2 bg-[#FC6C85] text-white rounded-lg hover:bg-[#e85c73]">
           📄 Ver Curriculum
        </a>
    </div>
    @endif
</div>
@endsection
