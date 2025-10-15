@extends('layouts.app')

@section('content')
<div class="chat-wrapper">
    <div class="chat-container">
        <!-- HEADER -->
        <div class="chat-header">
            <div class="bot-avatar">
                <img src="{{ asset('images/doctor.jpeg') }}" alt="DermaBot">
            </div>

            <div class="chat-title">
                <h3>DermaBot</h3>
                <span>{{ $conversacion->titulo ?? 'Sin título' }}</span>
            </div>

            <div class="dropdown-icon">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>

        <!-- MENSAJES -->
        <div id="mensajes" class="messages-container">
            @foreach($mensajes as $mensaje)
                @php
                    $esPregunta = Str::endsWith(trim($mensaje->contenido), '?');
                @endphp
                <div class="d-flex {{ $mensaje->tipo === 'usuario' ? 'justify-content-end' : 'justify-content-start' }} mb-2">
                    <div class="bubble 
                        {{ $mensaje->tipo === 'usuario' ? 'bubble-usuario' : ($esPregunta ? 'bubble-pregunta' : 'bubble-bot') }}">
                        <small class="fw-bold d-block mb-1">
                            {{ $mensaje->tipo === 'usuario' ? 'Tú' : 'DermaBot' }}
                        </small>
                        {!! nl2br(e($mensaje->contenido)) !!}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Indicador de escritura -->
        <div id="typing-indicator-container" class="d-flex justify-content-start mb-2" style="display: none;">
            <div class="bubble bubble-bot typing-indicator">
                <small class="fw-bold d-block mb-1">DermaBot</small>
                <span></span><span></span><span></span>
            </div>
        </div>

        <!-- INPUT -->
        <div class="input-container">
            <form id="chat-form" method="POST" action="{{ route('chat.mensaje', $conversacion) }}">
                @csrf
                <div class="input-group">
                    <textarea id="mensaje" name="mensaje" class="form-control message-input" rows="1" placeholder="Escribe tu mensaje..." required></textarea>
                    <button type="submit" class="btn send-button">
                        <img src="{{ asset('images/enviar.png') }}" alt="Enviar" class="send-icon">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('chat-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const textarea = document.getElementById('mensaje');
    const mensajesDiv = document.getElementById('mensajes');
    const typingDiv = document.getElementById('typing-indicator-container');
    const boton = this.querySelector('button');

    const texto = textarea.value.trim();
    if (!texto) return;

    boton.disabled = true;

    // Mostrar mensaje del usuario
    mensajesDiv.insertAdjacentHTML('beforeend', `
        <div class="d-flex justify-content-end mb-2">
            <div class="bubble bubble-usuario">
                <small class="fw-bold d-block mb-1">Tú</small>
                ${texto.replace(/\n/g, '<br>')}
            </div>
        </div>
    `);

    textarea.value = '';
    mensajesDiv.scrollTop = mensajesDiv.scrollHeight;

    // Mostrar indicador de escritura
    typingDiv.style.display = 'flex';

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ mensaje: texto })
        });

        const data = await response.json();

        // Ocultar indicador de escritura
        typingDiv.style.display = 'none';

        if (data.success) {
            const esPregunta = data.respuesta.trim().endsWith('?');
            mensajesDiv.insertAdjacentHTML('beforeend', `
                <div class="d-flex justify-content-start mb-2">
                    <div class="bubble ${esPregunta ? 'bubble-pregunta' : 'bubble-bot'}">
                        <small class="fw-bold d-block mb-1">DermaBot</small>
                        ${data.respuesta}
                    </div>
                </div>
            `);
            mensajesDiv.scrollTop = mensajesDiv.scrollHeight;
        } else {
            alert('Error: ' + (data.error ?? 'No se pudo enviar el mensaje'));
        }
    } catch (error) {
        console.error('Error:', error);
        typingDiv.style.display = 'none';
        alert('Ocurrió un problema al enviar el mensaje.');
    }

    boton.disabled = false;
});

document.addEventListener('DOMContentLoaded', function () {
    const m = document.getElementById('mensajes');
    if (m) m.scrollTop = m.scrollHeight;
});
</script>

<style>
/* Wrapper general */
.chat-wrapper {
    display: flex;
    justify-content: center;
    padding: 20px;
    background: linear-gradient(135deg, #fce4ec 0%, #f8bbd9 100%);
    min-height: 100vh;
}

.chat-container {
    width: 100%;
    max-width: 900px;
    max-height: 85vh;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(233, 30, 99, 0.15);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.chat-header {
    background: linear-gradient(135deg, #f48fb1 0%, #f06292 100%);
    color: white;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.bot-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    flex: 0 0 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.12);
}

.bot-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.chat-title { flex: 1; min-width: 0; }
.chat-title h3 { margin: 0; font-size: 18px; font-weight: 600; }
.chat-title span { display: block; font-size: 13px; opacity: 0.95; }

.messages-container {
    background: #fafafa;
    padding: 18px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    overflow-y: auto;
    flex: 1 1 auto;
}

.bubble {
    max-width: 70%;
    padding: 12px 15px;
    border-radius: 18px;
    white-space: pre-wrap;
    box-shadow: 0px 2px 6px rgba(244, 143, 177, 0.12);
    animation: fadeInUp 0.25s ease;
    word-wrap: break-word;
    font-size: 14px;
}

.justify-content-end .bubble-usuario {
    background: linear-gradient(135deg, #e91e63, #c2185b);
    color: white;
    margin-left: auto;
    border-top-right-radius: 4px;
}

.justify-content-start .bubble-bot,
.justify-content-start .bubble-pregunta {
    margin-right: auto;
    border-top-left-radius: 4px;
    color: #424242;
}

.bubble-bot { background: white; border: 1px solid rgba(244, 143, 177, 0.28); }
.bubble-pregunta { background: #fce4ec; }

/* Indicador de escritura */
.typing-indicator span {
    width: 6px;
    height: 6px;
    background-color: #f06292;
    border-radius: 50%;
    display: inline-block;
    margin: 0 2px;
    animation: blink 1.4s infinite both;
}
.typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
.typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

@keyframes blink {
    0% { opacity: 0.2; }
    20% { opacity: 1; }
    100% { opacity: 0.2; }
}

.input-container { padding: 16px; background: white; border-top: 1px solid rgba(244, 143, 177, 0.08); }

.input-group {
    display: flex;
    align-items: center;
    background: #f8f9fa;
    border-radius: 25px;
    padding: 6px;
    gap: 8px;
}

.message-input {
    flex: 1;
    border: none;
    background: transparent;
    resize: none;
    outline: none;
    font-family: inherit;
    font-size: 14px;
    padding: 8px 12px;
    box-shadow: none;
}

.send-button {
    background: none;
    border: none;
    padding: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}
.send-button:hover { transform: scale(1.05); }
.send-icon { width: 40px; height: 40px; object-fit: contain; }

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
    .chat-wrapper { padding: 10px; }
    .bubble { max-width: 85%; }
}
</style>
@endsection
