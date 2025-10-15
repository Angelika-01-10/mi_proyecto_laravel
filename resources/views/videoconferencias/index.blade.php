@extends('layouts.app')

@section('title', 'Videoconferencias')

@section('content')
<style>
/* Encabezado centrado */
.videoconf-header {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 300px;
    text-align: center;
    margin-bottom: 2rem;
}

.section-description {
    font-size: 1rem;
    color: #4b5563;
    max-width: 600px;
}

/* Lista de videoconferencias */
.videoconf-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
    padding: 0;
    margin: 0;
    list-style: none;
}

/* Tarjeta individual con borde sandía */
.videoconf-item {
    display: flex;
    flex-direction: column;
    background: white;
    border: 2px solid #FC6C85; /* borde sandía */
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.videoconf-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}

/* Contenedor de la imagen */
.videoconf-img-container {
    width: 200px;
    height: 150px;
    margin: 1rem auto;
    overflow: hidden;
    border-radius: 12px;
}

.videoconf-img-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.videoconf-img-container img:hover {
    transform: scale(1.05);
}

/* Contenido de la tarjeta */
.videoconf-content {
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.videoconf-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
}

.videoconf-meta {
    font-size: 0.9rem;
    color: #6b7280;
}

/* Botones horizontales solo emoji */
.action-btns {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
    justify-content: flex-start;
}

.action-btn {
    background: transparent;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    transition: transform 0.2s;
}

.action-btn:hover {
    transform: scale(1.2);
}

.create-btn {
    display: inline-block;
    padding: 0.5rem 1rem;
    background-color: #1f2937;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    transition: background 0.2s;
}
.create-btn:hover { background-color: #111827; }

.no-videoconf {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}
</style>

{{-- Encabezado --}}
<div class="videoconf-header">
    <h1 class="page-title text-3xl font-bold mb-2">📹 Videoconferencias</h1>
    <p class="section-description">
        "Videoconferencias exclusivas con expertos para aprender a cuidar y mantener tu piel sana."
    </p>

    {{-- Botón Crear solo para dermatólogos y admin --}}
    @auth
        @if(Auth::user()->hasRole('dermatologo') || Auth::user()->hasRole('admin'))
            <a href="{{ route('videoconferencias.create') }}" class="create-btn mt-4 inline-block">
                ➕ Crear videoconferencia
            </a>
        @endif
    @endauth
</div>
{{-- Mensajes flash --}}
@if(session('success'))
    <div style="background:#d1fae5; color:#065f46; padding:12px; border-radius:8px; margin:1rem auto; max-width:600px; text-align:center;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background:#fee2e2; color:#991b1b; padding:12px; border-radius:8px; margin:1rem auto; max-width:600px; text-align:center;">
        {{ session('error') }}
    </div>
@endif

{{-- Lista de videoconferencias --}}
@if($videoconferencias->count() > 0)
    <ul class="videoconf-list">
        @foreach($videoconferencias as $vc)
            <li class="videoconf-item">
                {{-- Imagen arriba --}}
                @if($vc->imagen)
                    <div class="videoconf-img-container">
                        <img src="{{ asset('storage/' . $vc->imagen) }}" 
                             alt="Imagen de {{ $vc->titulo }}">
                    </div>
                @endif

                {{-- Contenido debajo de la imagen --}}
                <div class="videoconf-content">
                    <h2 class="videoconf-title">{{ $vc->titulo }}</h2>

                    <div class="videoconf-meta">
                        <span class="meta-label">📅 Fecha:</span> 
                        <span class="meta-value">{{ $vc->fecha }}</span>
                    </div>

                    @if($vc->descripcion)
                        <div class="videoconf-meta">
                            <span class="meta-label">📝 Descripción:</span> 
                            <span class="meta-value">{{ $vc->descripcion }}</span>
                        </div>
                    @endif

                    {{-- Mostrar número de inscritos solo a dermatólogo o admin --}}
@auth
    @if(Auth::user()->hasRole('dermatologo') || Auth::user()->hasRole('admin'))
        <div class="videoconf-meta">
            <span class="meta-label">👥 Inscritos:</span>
            <span class="meta-value">{{ $vc->pacientes->count() }}</span>
        </div>
    @endif
@endauth


                    <div class="action-btns">
                        {{-- Botones solo para dermatólogo o admin --}}
                        @auth
                            @if(Auth::user()->hasRole('dermatologo') || Auth::user()->hasRole('admin'))
                                <a href="{{ route('videoconferencias.edit', $vc->id) }}" class="action-btn" title="Editar">✏️</a>
                                <form action="{{ route('videoconferencias.destroy', $vc->id) }}" method="POST"
                                      onsubmit="return confirm('¿Estás seguro de eliminar esta videoconferencia?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn" title="Eliminar">🗑️</button>
                                </form>
                            @endif
                        @endauth

                        @auth
    @if(Auth::user()->hasRole('dermatologo'))
        <form action="{{ route('videoconferencias.notificar', $vc->id) }}" method="POST" class="mt-2">
            @csrf
            <button type="submit" class="reserve-btn">
                🚨 Notificar inicio
            </button>
        </form>
    @endif
@endauth



                        {{-- Botón Reservar solo para pacientes --}}
                        @auth
                            @if(Auth::user()->hasRole('paciente'))
                                 <form action="{{ route('videoconferencias.inscribirse', $vc->id) }}" method="POST" class="reserve-form">
                                @csrf
                                 <button type="submit" class="reserve-btn">
                                 ❤️ Reservar
                               </button>
                               </form>
                            @endif
                        @endauth
                    </div>

                </div>
            </li>
        @endforeach
    </ul>
@else
    <div class="no-videoconf">
        <div class="no-videoconf-icon">🎭</div>
        <p>No hay videoconferencias disponibles.</p>
    </div>
@endif

@endsection
