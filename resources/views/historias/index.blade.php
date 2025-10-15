@extends('layouts.app')

@section('title', 'Mis Historias de Piel')

@section('content')
<style>
/* Layout principal */
.historias-container {
    display: grid;
    grid-template-columns: 1fr 320px; /* contenido + sidebar */
    gap: 2rem;
    padding: 2rem;
}

/* Tarjetas de historia */
.historia-card {
    background: white;
    border: 2px solid #FC6C85; 
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}
.historia-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}

.historia-header {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.historia-header img {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
}
.historia-content {
    margin-top: 1rem;
}
.historia-meta {
    font-size: 0.9rem;
    color: #6b7280;
    margin-top: 0.3rem;
}

/* Botones editar/eliminar */
.historia-actions {
    margin-top: 1rem;
    display: flex;
    gap: 0.5rem;
}
.historia-actions a,
.historia-actions button {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s, transform 0.2s;
    text-decoration: none;
    color: white;
}
.edit-btn {
    background-color: #3b82f6;
}
.edit-btn:hover { background-color: #2563eb; transform: scale(1.05); }
.delete-btn {
    background-color: #ef4444;
}
.delete-btn:hover { background-color: #b91c1c; transform: scale(1.05); }

/* Sidebar de dermatólogos */
.sidebar {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    padding: 1rem;
    border: 2px solid #FC6C85;
    max-height: 80vh;
    overflow-y: auto;
}
.sidebar h3 {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1rem;
    text-align: center;
}
.derma-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 0.3rem;
    padding: 0.8rem;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s;
}
.derma-item:hover {
    background: #fff0f2;
}
.derma-item img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 0.5rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.derma-name {
    font-weight: 600;
    font-size: 0.95rem;
    color: #333;
}
.derma-btn {
    background-color: #FC6C85;
    border: none;
    color: white;
    font-size: 1.2rem;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s, transform 0.2s;
}
.derma-btn:hover {
    background-color: #e85c73;
    transform: scale(1.1);
}
.derma-item .flex {
    justify-content: center;
}
</style>

<div class="videoconf-header">
    <h1 class="page-title text-3xl font-bold mb-2">📋 Mis Historias de Piel</h1>
    <p class="section-description">Aquí puedes consultar tus registros dermatológicos y compartirlos con especialistas.</p>
    <a href="{{ route('historias.create') }}" class="create-btn mt-4 inline-block px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
        ➕ Crear nueva historia
    </a>
</div>

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

<div class="historias-container">
    <!-- Lista de historias -->
    <div>
        @if($historias->count() > 0)
            @foreach($historias as $historia)
                <div class="historia-card">
                    <div class="historia-header">
                        @if($historia->foto_perfil)
                            <img src="{{ asset('storage/' . $historia->foto_perfil) }}" alt="Foto de perfil">
                        @else
                            <img src="https://via.placeholder.com/70" alt="Sin foto">
                        @endif
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Historia #{{ $historia->id }}</h3>
                            <p class="text-sm text-gray-500">{{ $historia->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="historia-content">
                        <p class="text-gray-700">{{ $historia->descripcion_sintomas }}</p>
                        <p class="historia-meta">Estado: <strong>{{ ucfirst($historia->estado ?? 'pendiente') }}</strong></p>
                        <p class="historia-meta">Diagnóstico: {{ $historia->respuesta_diagnostico ?? 'Sin respuesta aún' }}</p>
                        @if($historia->documento_medico)
                            <a href="{{ asset('storage/' . $historia->documento_medico) }}" target="_blank" class="text-blue-600 text-sm underline mt-1 inline-block">
                                📎 Ver documento adjunto
                            </a>
                        @endif
                    </div>

                    @if($historia->estado !== 'cerrada')
                    <div class="historia-actions">
                        <a href="{{ route('historias.edit', $historia->id) }}" class="edit-btn">✏️ Editar</a>
                        <form action="{{ route('historias.destroy', $historia->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Seguro quieres eliminar esta historia?');" class="delete-btn">🗑️ Eliminar</button>
                        </form>
                    </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="no-videoconf text-center mt-10">
                <div class="no-videoconf-icon text-4xl">🩹</div>
                <p>No tienes historias registradas aún.</p>
            </div>
        @endif
    </div>

    <!-- Sidebar dermatólogos -->
    <!-- Sidebar dermatólogos -->
<div class="sidebar" style="background: linear-gradient(180deg, #fff 0%, #fff5f7 100%); border: 2px solid #FC6C85;">
    <h3 style="text-align:center; font-size:1.3rem; font-weight:700; color:#e85c73; margin-bottom:1rem;">
        👩‍⚕️ Dermatólogos disponibles
    </h3>

    @forelse($dermatologos as $derma)
        <div class="derma-item" style="background:white; border-radius:12px; padding:1rem; margin-bottom:1rem; box-shadow:0 3px 8px rgba(0,0,0,0.08); transition:all 0.3s ease; border:1px solid #ffe0e6;">
            <div style="display:flex; flex-direction:column; align-items:center;">
                @if($derma->foto)
                    <img src="{{ asset('storage/' . $derma->foto) }}" 
                         alt="Foto de {{ $derma->name }}"
                         style="width:90px; height:90px; border-radius:50%; object-fit:cover; box-shadow:0 2px 10px rgba(252,108,133,0.4); border:3px solid #fff;">
                @else
                    <img src="https://via.placeholder.com/90" 
                         alt="Sin foto" 
                         style="width:90px; height:90px; border-radius:50%; object-fit:cover; box-shadow:0 2px 10px rgba(252,108,133,0.4); border:3px solid #fff;">
                @endif

                <div style="margin-top:0.5rem;">
                    <div class="derma-name" style="font-weight:700; color:#333; font-size:1rem;">{{ $derma->name }}</div>
                    <div style="font-size:0.8rem; color:#6b7280;">{{ $derma->email }}</div>
                </div>

                <div style="display:flex; gap:0.5rem; margin-top:0.8rem;">
                    <!-- Botón enviar historia -->
                    <form action="{{ route('historias.asignar', $derma->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                title="Enviar historia al dermatólogo"
                                style="background-color:#FC6C85; border:none; color:white; font-size:1.2rem; border-radius:50%; width:38px; height:38px; display:flex; align-items:center; justify-content:center; transition:all 0.3s;">
                            💬
                        </button>
                    </form>

                    <!-- Botón ver perfil -->
                    <a href="{{ route('dermatologo.ver-perfil', $derma->id) }}" 
                       title="Ver perfil del dermatólogo"
                       style="background-color:#10b981; color:white; border-radius:50%; width:38px; height:38px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; text-decoration:none; transition:all 0.3s;">
                        👀
                    </a>
                </div>
            </div>
        </div>
    @empty
        <p style="text-align:center; color:#6b7280; font-size:0.9rem;">No hay dermatólogos disponibles.</p>
    @endforelse
</div>

</div>
@endsection
