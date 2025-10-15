@extends('layouts.app')

@section('title', $perfil ? 'Editar Perfil Profesional' : 'Crear Perfil Profesional')

@section('content')
<style>
.perfil-container {
    max-width: 900px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.page-header {
    text-align: center;
    margin-bottom: 2rem;
}

.page-header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.page-header p {
    color: #6b7280;
    font-size: 1rem;
}

.perfil-form-card {
    background: white;
    border: 2px solid #FC6C85;
    border-radius: 16px;
    padding: 2.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
}

.form-section {
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f3f4f6;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-input, .form-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-family: inherit;
    font-size: 0.95rem;
    transition: all 0.2s;
}

.form-input:focus, .form-textarea:focus {
    outline: none;
    border-color: #FC6C85;
    box-shadow: 0 0 0 3px rgba(252, 108, 133, 0.1);
}

.form-textarea {
    resize: vertical;
    min-height: 120px;
    line-height: 1.6;
}

.file-upload-wrapper {
    position: relative;
}

.preview-section {
    margin-top: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 8px;
}

.preview-image {
    max-width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 12px;
    border: 3px solid #FC6C85;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.file-info {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.75rem;
    padding: 0.6rem 1rem;
    background: #f0fdf4;
    border: 1px solid #86efac;
    border-radius: 8px;
    font-size: 0.9rem;
}

.file-info a {
    color: #059669;
    text-decoration: none;
    font-weight: 500;
}

.file-info a:hover {
    text-decoration: underline;
}

.form-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 2.5rem;
    padding-top: 2rem;
    border-top: 2px solid #f3f4f6;
}

.btn-primary {
    background: linear-gradient(135deg, #FC6C85 0%, #e85c73 100%);
    color: white;
    border: none;
    padding: 0.875rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 2px 8px rgba(252, 108, 133, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(252, 108, 133, 0.4);
}

.btn-secondary {
    background-color: #f3f4f6;
    color: #374151;
    border: 2px solid #e5e7eb;
    padding: 0.875rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all 0.2s;
}

.btn-secondary:hover {
    background-color: #e5e7eb;
    border-color: #d1d5db;
    color: #1f2937;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #86efac;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

.alert-danger ul {
    margin: 0.5rem 0 0 1.5rem;
    padding: 0;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .perfil-form-card {
        padding: 1.5rem;
    }
}
</style>

<div class="perfil-container">
    <div class="page-header">
        <h1>{{ $perfil ? '✏️ Editar' : '➕ Crear' }} Perfil Profesional</h1>
        <p>Completa tu información profesional para que los pacientes te conozcan mejor</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Por favor corrige los siguientes errores:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="perfil-form-card">
        <form method="POST" action="{{ route('dermatologo.guardar-perfil') }}" enctype="multipart/form-data">
            @csrf

            <!-- Sección: Información Profesional -->
            <div class="form-section">
                <h2 class="section-title">📋 Información Profesional</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Título Profesional</label>
                        <input type="text" 
                               name="titulo_profesional" 
                               class="form-input" 
                               value="{{ old('titulo_profesional', $perfil->titulo_profesional ?? '') }}"
                               placeholder="Médico Cirujano, Dermatólogo">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Especialidad</label>
                        <input type="text" 
                               name="especialidad" 
                               class="form-input" 
                               value="{{ old('especialidad', $perfil->especialidad ?? '') }}"
                               placeholder="Dermatología Clínica">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Número de Licencia</label>
                        <input type="text" 
                               name="numero_licencia" 
                               class="form-input" 
                               value="{{ old('numero_licencia', $perfil->numero_licencia ?? '') }}"
                               placeholder="12345-COL">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Años de Experiencia</label>
                        <input type="number" 
                               name="años_experiencia" 
                               class="form-input" 
                               value="{{ old('años_experiencia', $perfil->años_experiencia ?? '') }}"
                               min="0"
                               placeholder="5">
                    </div>
                </div>
            </div>

            <!-- Sección: Contacto -->
            <div class="form-section">
                <h2 class="section-title">📞 Información de Contacto</h2>
                
                <div class="form-group">
                    <label class="form-label">Teléfono</label>
                    <input type="text" 
                           name="telefono" 
                           class="form-input" 
                           value="{{ old('telefono', $perfil->telefono ?? '') }}"
                           placeholder="+57 310 555 1234">
                </div>
            </div>

            <!-- Sección: Biografía -->
            <div class="form-section">
                <h2 class="section-title">✍️ Acerca de ti</h2>
                
                <div class="form-group">
                    <label class="form-label">Biografía Profesional</label>
                    <textarea name="biografia" 
                              class="form-textarea" 
                              placeholder="Describe tu trayectoria, áreas de interés y enfoque profesional...">{{ old('biografia', $perfil->biografia ?? '') }}</textarea>
                </div>
            </div>

            <!-- Sección: Archivos -->
            <div class="form-section">
                <h2 class="section-title">📁 Documentos y Fotografía</h2>
                
                <div class="form-group">
                    <label class="form-label">Foto de Perfil</label>
                    <input type="file" 
                           name="foto_perfil" 
                           class="form-input" 
                           accept="image/jpeg,image/png,image/jpg">
                    
                    @if($perfil && $perfil->foto_perfil)
                        <div class="preview-section">
                            <p style="font-size: 0.9rem; color: #6b7280; margin-bottom: 0.5rem;">Foto actual:</p>
                            <img src="{{ asset('storage/' . $perfil->foto_perfil) }}" 
                                 alt="Foto actual" 
                                 class="preview-image">
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Curriculum Vitae (PDF)</label>
                    <input type="file" 
                           name="curriculum" 
                           class="form-input" 
                           accept=".pdf">
                    
                    @if($perfil && $perfil->curriculum)
                        <div class="file-info">
                            📄 CV actual disponible - 
                            <a href="{{ asset('storage/' . $perfil->curriculum) }}" 
                               target="_blank">Ver documento</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Botones -->
            <div class="form-buttons">
                <button type="submit" class="btn-primary">
                    {{ $perfil ? '💾 Guardar Cambios' : '✅ Crear Perfil' }}
                </button>
                <a href="{{ route('historias.index') }}" class="btn-secondary">
                    ❌ Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection