@extends('layouts.app')

@section('content')
<style>
    .chat-container {
        height: 100vh;
        display: flex;
        overflow: hidden;
    }

    /* Sidebar */
    .chat-sidebar {
        width: 300px;
        background: #ffe3ec;
        border-right: 1px solid #ffc2d1;
        display: flex;
        flex-direction: column;
    }

    .chat-header {
        background: #fff;
        padding: 15px;
        font-weight: bold;
        border-bottom: 1px solid #ffc2d1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Conversaciones */
    .chat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 15px;
        border-bottom: 1px solid #ffc2d1;
        background: #fff;
        transition: 0.2s ease;
    }

    .chat-item:hover {
        background: #ffeaf0;
    }

    .chat-link {
        text-decoration: none;
        color: #000;
        flex-grow: 1;
    }

    /* Botón de eliminar */
    .delete-btn {
        background: #fff;
        border: none;
        color: #e63946;
        cursor: pointer;
        padding: 6px;
        border-radius: 6px;
        transition: background 0.2s ease;
    }

    .delete-btn:hover {
        background: #ffe3e3;
        color: #b5171d;
    }

    /* Panel principal */
    .chat-main {
        flex: 1;
        display: flex;
        justify-content: flex-start; /* Alinear a la izquierda */
        align-items: flex-start; /* Alinear arriba */
        background: #fff5f8;
        padding: 0; /* Eliminar padding para alineación perfecta */
    }

    /* Caja de bienvenida */
    .welcome-box {
        position: relative;
        width: 100%;
        height: 100vh; /* Altura completa de la ventana */
        border-radius: 0; /* Sin bordes redondeados para alineación perfecta */
        overflow: hidden;
        background: #fff0f5;
        display: flex; /* Cambio a flexbox */
    }

    /* Imagen a la izquierda ocupando toda la altura */
    .bg-behind {
        width: 400px; /* Más ancha que el sidebar manteniendo alineación */
        height: 100%; /* Ocupa toda la altura del contenedor */
        background-image: url('{{ asset("images/dermabot.jpeg") }}');
        background-size: cover; /* Cubre todo el área disponible */
        background-position: center center;
        background-repeat: no-repeat;
        opacity: 0.8; /* Más visible */
        border-radius: 0; /* Sin bordes redondeados para alineación perfecta */
    }

    /* Contenido a la derecha */
    .welcome-content {
        flex: 1; /* Ocupa el resto del espacio */
        padding: 24px 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    /* Eliminar la capa oscura ya que ahora la imagen está separada */
    .welcome-box::before {
        display: none;
    }

    .btn-start {
        background: #ff3366;
        color: white;
        font-weight: bold;
        border-radius: 30px;
        padding: 12px 30px;
        border: none;
        margin-top: 15px;
        transition: background 0.3s;
    }

    .btn-start:hover {
        background: #ff1744;
    }

    .alert-info {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid #ffc2d1;
        border-radius: 10px;
        text-align: left;
        padding: 10px;
        margin-top: 15px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .chat-main {
            padding: 20px; /* Restaurar padding en móviles */
        }
        
        .welcome-box {
            flex-direction: column;
            height: auto;
            border-radius: 15px; /* Restaurar bordes en móviles */
            max-width: 600px;
        }
        
        .bg-behind {
            width: 100%;
            height: 150px;
            border-radius: 15px 15px 0 0;
        }
        
        .welcome-content {
            padding: 18px 12px;
        }
        
        .welcome-content h2 { 
            font-size: 1.4rem; 
        }
        
        .welcome-content p { 
            font-size: 0.95rem; 
        }
    }

    @media (max-width: 576px) {
        .bg-behind {
            height: 120px;
        }
    }
</style>

<div class="chat-container">
    <!-- Sidebar -->
    <div class="chat-sidebar">
        <div class="chat-header">
            <span><i class="fas fa-comments me-2"></i> Mis Conversaciones</span>
            <form action="{{ route('chat.crear') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-light">
                    <i class="fas fa-plus"></i>
                </button>
            </form>
        </div>

        @foreach($conversaciones as $conv)
            <div class="chat-item">
                <a href="{{ route('chat.mostrar', $conv) }}" class="chat-link">
                    <div class="fw-bold text-truncate">{{ $conv->titulo ?? 'Consulta dermatológica' }}</div>
                    <small class="text-muted">{{ $conv->updated_at->diffForHumans() }}</small>
                </a>

                <!-- Botón de eliminar -->
                <form action="{{ route('chat.eliminar', $conv) }}" method="POST" onsubmit="return confirm('¿Eliminar esta conversación?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn" title="Eliminar">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        @endforeach
    </div>

    <!-- Panel principal -->
    <div class="chat-main">
        <div class="welcome-box">
            <!-- Imagen a la izquierda ocupando toda la altura -->
            <div class="bg-behind" aria-hidden="true"></div>

            <!-- Contenido visible a la derecha -->
            <div class="welcome-content">
                <h2 class="fw-bold">Bienvenid@ {{ Auth::user()->name ?? 'Usuario' }}</h2>
                <p>
                    Soy <strong class="text-danger">DermaBot Professional</strong>, tu asistente dermatológico virtual.
                </p>

                <div class="alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong> Aviso:</strong> Esta herramienta es informativa y de apoyo.  
                    Para un diagnóstico real, consulta a un médico.
                </div>

                <form action="{{ route('chat.crear') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-start">
                        <i class="fas fa-plus"></i> INICIAR CONSULTA
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection