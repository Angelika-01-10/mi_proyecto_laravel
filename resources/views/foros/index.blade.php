@extends('layouts.app')

@section('title', 'Foros')

@section('content')
<style>
.foros-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 1rem;
}

/* Card estilo Instagram */
.foro-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    margin-bottom: 2rem;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

/* Imagen principal estilo Instagram */
.foro-main-img {
    width: 100%;
    max-height: 400px;
    overflow: hidden;
    background: #000;
}
.foro-main-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* Cuerpo del foro */
.foro-body {
    padding: 1rem;
}
.foro-title {
    font-size: 1.1rem;
    font-weight: bold;
    margin-bottom: 0.3rem;
    color: #111827;
}
.foro-content {
    font-size: 0.95rem;
    color: #374151;
    margin-bottom: 0.5rem;
}
.foro-info {
    font-size: 0.8rem;
    color: #6b7280;
}

/* Botones de acción */
.action-btns {
    display: flex;
    gap: 0.3rem;
    margin-top: 0.5rem;
}
.action-btn {
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    transition: transform 0.2s;
}
.action-btn:hover {
    transform: scale(1.2);
}

/* Comentarios */
.comentarios-list {
    padding: 0.8rem 1rem;
    border-top: 1px solid #f3f4f6;
}
.comentario {
    margin-bottom: 0.8rem;
}
.comentario-bubble {
    background: #f9fafb;
    border-radius: 12px;
    padding: 0.6rem 0.8rem;
    font-size: 0.9rem;
    color: #111827;
}
.comentario-autor {
    font-weight: 600;
    font-size: 0.85rem;
    margin-right: 0.3rem;
}
.comentario .action-btns {
    margin-left: 0.5rem;
    display: inline-flex;
}

/* Formulario de comentar */
.comentario-form textarea {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 12px;
    padding: 0.6rem;
    font-size: 0.9rem;
    resize: none;
    margin-bottom: 0.5rem;
}
.comentario-form button {
    background: #ec4899;
    color: white;
    padding: 0.4rem 1rem;
    border-radius: 9999px;
    font-size: 0.9rem;
    transition: background 0.2s;
}
.comentario-form button:hover {
    background: #db2777;
}

/* Crear foro */
.create-foro-btn {
    display: inline-block;
    padding: 0.5rem 1.2rem;
    background: linear-gradient(135deg, #fc6c85, #ec4899);
    color: white;
    font-weight: 600;
    border-radius: 9999px;
    text-decoration: none;
    margin-bottom: 1rem;
    transition: 0.2s;
}
.create-foro-btn:hover { background: #db2777; }
</style>

<div class="foros-container">

    <h1 class="text-2xl font-bold text-center mb-6">SkinLove</h1>

    {{-- Botón crear foro --}}
    @auth
        @if(Auth::user()->hasAnyRole(['dermatologo', 'administrador']))
            <div class="flex justify-center mb-6">
                <a href="{{ route('foros.create') }}" class="create-foro-btn">
                    ➕ Crear Foro
                </a>
            </div>
        @endif
    @endauth

    @if($foros->isEmpty())
        <p class="text-center text-gray-500">No hay foros disponibles.</p>
    @else
        @foreach($foros as $foro)
            <div class="foro-card">
                {{-- Imagen principal tipo Instagram --}}
                @if($foro->imagen)
                    <div class="foro-main-img">
                        <img src="{{ asset('storage/' . $foro->imagen) }}" alt="Imagen foro">
                    </div>
                @endif

                {{-- Texto debajo --}}
                <div class="foro-body">
                    <a href="{{ route('foros.show', $foro->id) }}" class="foro-title hover:underline">
                        {{ $foro->titulo }}
                    </a>
                    <p class="foro-content">{{ $foro->contenido }}</p>
                    <p class="foro-info">Creado por {{ $foro->user->name }} • {{ $foro->created_at->diffForHumans() }}</p>

                    {{-- Botones de editar/eliminar foro (solo autor o admin) --}}
                    @auth
                        @if(Auth::id() === $foro->user_id || Auth::user()->hasRole('administrador'))
                            <div class="action-btns">
                                <a href="{{ route('foros.edit', $foro->id) }}" class="action-btn" title="Editar">✏️</a>
                                <form action="{{ route('foros.destroy', $foro->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este foro?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn" title="Eliminar">🗑️</button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>

                {{-- Comentarios --}}
                <div class="comentarios-list">
                    <p class="text-sm text-gray-500 mb-2">💬 {{ $foro->comentarios->count() }} comentarios</p>

                    @if($foro->comentarios->isNotEmpty())
                        @foreach($foro->comentarios as $comentario)
                            <div class="comentario">
                                <div class="comentario-bubble">
                                    <span class="comentario-autor">{{ $comentario->user->name }}</span>
                                    {{ $comentario->contenido }}
                                </div>

                                  {{-- Imagen del comentario, si existe --}}
@if($comentario->imagen)
    <div class="mt-2">
        <img src="{{ asset('storage/' . $comentario->imagen) }}" 
             alt="Imagen comentario" 
             style="max-width:150px; border-radius:8px;">
    </div>
@endif


                                {{-- Botones editar/eliminar comentario (solo autor o admin) --}}
                                @auth
                                    @if(Auth::id() === $comentario->user_id || Auth::user()->hasRole('administrador'))
                                        <div class="action-btns">
                                            <a href="{{ route('comentarios.edit', $comentario->id) }}" class="action-btn" title="Editar">✏️</a>
                                            <form action="{{ route('comentarios.destroy', $comentario->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este comentario?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn" title="Eliminar">🗑️</button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        @endforeach
                    @endif

                    {{-- Formulario de comentar --}}
                    @auth
                        <form action="{{ route('foros.comentar', $foro->id) }}" method="POST" enctype="multipart/form-data" class="comentario-form mt-2">
                            @csrf
                            <textarea name="contenido" rows="2" placeholder="Escribe un comentario..." required></textarea>
                            <input type="file" name="imagen" accept="image/*" class="mb-2">
                            <button type="submit">Comentar</button>
                        </form>
                    @endauth
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
