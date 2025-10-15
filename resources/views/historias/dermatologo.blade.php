@extends('layouts.app')

@section('title', 'Panel del Dermatólogo')

@section('content')
<style>
/* Mismos estilos de la vista anterior */
.historias-container {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
    padding: 2rem;
}

.perfil-card {
    background: white;
    border: 2px solid #FC6C85;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    height: fit-content;
    position: sticky;
    top: 2rem;
}

.perfil-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 1rem;
    display: block;
    border: 3px solid #FC6C85;
}

.perfil-info {
    text-align: center;
    margin-bottom: 1rem;
}

.perfil-nombre {
    font-size: 1.2rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.perfil-especialidad {
    font-size: 0.9rem;
    color: #6b7280;
    margin-bottom: 0.3rem;
}

.perfil-btn {
    background-color: #FC6C85;
    color: white;
    border: none;
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    width: 100%;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.2s;
    text-decoration: none;
    display: block;
    text-align: center;
}

.perfil-btn:hover {
    background-color: #e85c73;
    color: white;
}

.historia-card {
    background: white;
    border: 2px solid #FC6C85;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.historia-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.historia-header img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
}

.badge-estado {
    display: inline-block;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

.badge-revision {
    background: #fef3c7;
    color: #92400e;
}

.badge-cerrada {
    background: #d1fae5;
    color: #065f46;
}

.responder-form {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e5e7eb;
}

.responder-form textarea {
    width: 100%;
    padding: 0.8rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-family: inherit;
    resize: vertical;
    min-height: 100px;
}

.btn-submit {
    background-color: #10b981;
    color: white;
    border: none;
    padding: 0.6rem 1.5rem;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    margin-top: 0.5rem;
    transition: background 0.2s;
}

.btn-submit:hover {
    background-color: #059669;
}

.no-historias {
    text-align: center;
    padding: 3rem;
    color: #6b7280;
}

.sintomas-box {
    background: #f9fafb;
    padding: 1rem;
    border-radius: 8px;
    margin: 1rem 0;
}

.documento-link {
    display: inline-block;
    margin-top: 0.5rem;
    padding: 0.5rem 1rem;
    background: #f3f4f6;
    border-radius: 6px;
    text-decoration: none;
    color: #374151;
    font-size: 0.9rem;
}

.documento-link:hover {
    background: #e5e7eb;
}
</style>

<div class="videoconf-header">
    <h1 class="page-title text-3xl font-bold mb-2">👨‍⚕️ Panel del Dermatólogo</h1>
    <p class="section-description">
        Gestiona las historias de tus pacientes y actualiza tu perfil profesional.
    </p>
</div>

@if(session('success'))
    <div style="background:#d1fae5; color:#065f46; padding:12px; border-radius:8px; margin:1rem auto; max-width:800px; text-align:center;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background:#fee2e2; color:#991b1b; padding:12px; border-radius:8px; margin:1rem auto; max-width:800px; text-align:center;">
        {{ session('error') }}
    </div>
@endif

<div class="historias-container">
    <!-- Sidebar con perfil del dermatólogo -->
    <aside>
        <div class="perfil-card">
            @if($perfil && $perfil->foto_perfil)
                <img src="{{ asset('storage/' . $perfil->foto_perfil) }}" 
                     alt="Foto de perfil" class="perfil-avatar">
            @else
                <img src="https://via.placeholder.com/120" 
                     alt="Sin foto" class="perfil-avatar">
            @endif

            <div class="perfil-info">
                @if($perfil)
                    <div class="perfil-nombre">{{ Auth::user()->name }}</div>
                    <div class="perfil-especialidad">{{ $perfil->titulo_profesional ?? 'Dermatólogo' }}</div>
                    <div class="perfil-especialidad">{{ $perfil->especialidad ?? 'Especialidad no definida' }}</div>
                    
                    @if($perfil->años_experiencia)
                        <div class="text-sm text-gray-600 mt-2">
                            📅 {{ $perfil->años_experiencia }} años de experiencia
                        </div>
                    @endif
                    
                    @if($perfil->numero_licencia)
                        <div class="text-xs text-gray-500 mt-1">
                            Licencia: {{ $perfil->numero_licencia }}
                        </div>
                    @endif
                @else
                    <div class="perfil-nombre">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-gray-500 mb-3">
                        ⚠️ Completa tu perfil profesional
                    </div>
                @endif
            </div>

            <a href="{{ route('dermatologo.perfil') }}" class="perfil-btn">
                {{ $perfil ? '✏️ Editar Perfil' : '➕ Crear Perfil' }}
            </a>

            @if($perfil && $perfil->curriculum)
                <a href="{{ asset('storage/' . $perfil->curriculum) }}" 
                   target="_blank" 
                   class="perfil-btn mt-2" 
                   style="background:#6366f1;">
                    📄 Ver CV
                </a>
            @endif
        </div>
    </aside>

    <!-- Lista de historias asignadas -->
    <main>
        <h2 class="text-xl font-semibold mb-4">Historias de pacientes asignadas</h2>
        
        @if($historias->count() > 0)
            @foreach($historias as $historia)
                <div class="historia-card">
                    <div class="historia-header">
                        @if($historia->foto_perfil)
                            <img src="{{ asset('storage/' . $historia->foto_perfil) }}" 
                                 alt="Foto del paciente">
                        @else
                            <img src="https://via.placeholder.com/60" alt="Sin foto">
                        @endif
                        
                        <div style="flex: 1;">
                            <h3 class="text-lg font-semibold">
                                🩺 Historia #{{ $historia->id }} - {{ $historia->paciente->name ?? 'Desconocido' }}
                            </h3>
                            <p class="text-sm text-gray-500">
                                Enviado el {{ $historia->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <span class="badge-estado {{ $historia->estado == 'cerrada' ? 'badge-cerrada' : 'badge-revision' }}">
                            {{ ucfirst($historia->estado) }}
                        </span>
                    </div>

                    <div class="sintomas-box">
                        <h4 class="font-semibold text-gray-700 mb-2">📋 Descripción de síntomas:</h4>
                        <p class="text-gray-700">{{ $historia->descripcion_sintomas }}</p>
                    </div>

                    @if($historia->documento_medico)
                        <a href="{{ asset('storage/' . $historia->documento_medico) }}" 
                           target="_blank"
                           class="documento-link">
                            📎 Ver documento adjunto
                        </a>
                    @endif

                    @if($historia->estado == 'cerrada')
                        <div class="mt-3 p-3 bg-green-50 rounded-lg border border-green-200">
                            <h4 class="font-semibold text-green-800 mb-1">✅ Diagnóstico enviado:</h4>
                            <p class="text-gray-700">{{ $historia->respuesta_diagnostico }}</p>
                        </div>
                    @else
                        <form action="{{ route('historias.responder', $historia->id) }}" 
                              method="POST" 
                              class="responder-form">
                            @csrf
                            <label class="font-semibold text-gray-700 block mb-2">
                                🩹 Escribe tu diagnóstico:
                            </label>
                            <textarea name="respuesta_diagnostico" 
                                      required 
                                      placeholder="Describe el diagnóstico, recomendaciones y tratamiento para el paciente...">{{ $historia->respuesta_diagnostico }}</textarea>
                            <button type="submit" class="btn-submit">
                                💾 Guardar diagnóstico
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        @else
            <div class="no-historias">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📭</div>
                <p class="text-lg font-semibold">No tienes historias asignadas aún</p>
                <p class="text-sm text-gray-500">Los pacientes pueden enviarte historias desde su panel</p>
            </div>
        @endif
    </main>
</div>
@endsection