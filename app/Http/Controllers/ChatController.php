<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatConversacion;
use App\Services\HuggingFaceHttpService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct()
    {
        // Instanciamos el servicio de Hugging Face
        $this->chatService = new HuggingFaceHttpService();

        $this->middleware('auth');
    }

    /**
     * Mostrar todas las conversaciones del usuario
     */
    public function index()
    {
        $conversaciones = Auth::user()
            ->chatConversaciones()
            ->with(['ultimoMensaje'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('chat.index', compact('conversaciones'));
    }

    /**
     * Crear una nueva conversación
     */
    public function crear()
    {
        $conversacion = ChatConversacion::create([
            'user_id' => Auth::id(),
            'titulo' => null
        ]);

        return redirect()->route('chat.mostrar', $conversacion);
    }

    /**
     * Mostrar una conversación específica
     */
    public function mostrar(ChatConversacion $conversacion)
    {
        if ($conversacion->user_id !== Auth::id()) {
            abort(403, 'No tienes acceso a esta conversación');
        }

        $mensajes = $conversacion->mensajes()
            ->orderBy('created_at', 'asc')
            ->get();

        $conversaciones = Auth::user()
            ->chatConversaciones()
            ->with(['ultimoMensaje'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('chat.mostrar', compact('conversacion', 'mensajes', 'conversaciones'));
    }

    /**
     * Enviar mensaje
     */
    public function enviarMensaje(Request $request, ChatConversacion $conversacion): JsonResponse
    {
        $request->validate([
            'mensaje' => 'required|string|max:2000',
            'imagenes.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        if ($conversacion->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'error' => 'No tienes acceso a esta conversación'], 403);
        }

        try {
            // Procesar imágenes
            $imagenes = [];
            if ($request->hasFile('imagenes')) {
                foreach ($request->file('imagenes') as $imagen) {
                    $nombre = time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
                    $ruta = $imagen->storeAs('chat-imagenes', $nombre, 'public');
                    $imagenes[] = $ruta;
                }
            }

            // Enviar mensaje usando HuggingFaceHttpService
            $resultado = $this->chatService->enviarMensaje(
                $conversacion,
                $request->mensaje,
                $imagenes ?: null
            );

            if ($resultado['success']) {
                $texto = $resultado['respuesta'];

                if (is_string($texto) && str_starts_with($texto, '{')) {
                    $json = json_decode($texto, true);
                    if (json_last_error() === JSON_ERROR_NONE && isset($json['respuesta'])) {
                        $texto = $json['respuesta'];
                    }
                }

                $texto = nl2br(e($texto));

                return response()->json([
                    'success' => true,
                    'respuesta' => $texto,
                    'mensaje_id' => $resultado['mensaje_id'] ?? null,
                    'titulo_conversacion' => $conversacion->titulo,
                    'timestamp' => now()->format('H:i')
                ]);
            }

            return response()->json(['success' => false, 'error' => $resultado['error']], 500);

        } catch (\Exception $e) {
            Log::error('Error enviarMensaje: '.$e->getMessage(), [
                'user_id' => Auth::id(),
                'conversacion_id' => $conversacion->id,
                'trace' => $e->getTraceAsString()
            ]);

            $errorMessage = config('app.debug') 
                ? 'Error: ' . $e->getMessage() . ' en línea ' . $e->getLine()
                : 'Hubo un problema al procesar tu consulta. Por favor, intenta nuevamente.';

            return response()->json([
                'success' => false,
                'error' => $errorMessage
            ], 500);
        }
    }

    /**
     * Eliminar conversación
     */
    public function eliminar(ChatConversacion $conversacion)
    {
        if ($conversacion->user_id !== Auth::id()) {
            abort(403, 'No tienes acceso a esta conversación');
        }

        try {
            foreach ($conversacion->mensajes as $mensaje) {
                if ($mensaje->imagenes) {
                    foreach ($mensaje->imagenes as $imagen) {
                        if (Storage::disk('public')->exists($imagen)) {
                            Storage::disk('public')->delete($imagen);
                        }
                    }
                }
            }

            $conversacion->delete();

            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Conversación eliminada']);
            }

            return redirect()->route('chat.index')->with('success', 'Conversación eliminada');

        } catch (\Exception $e) {
            Log::error('Error eliminar conversación: '.$e->getMessage(), [
                'user_id' => Auth::id(),
                'conversacion_id' => $conversacion->id
            ]);

            return request()->ajax()
                ? response()->json(['success' => false, 'error' => 'Error al eliminar conversación'], 500)
                : back()->with('error', 'Error al eliminar conversación');
        }
    }

    /**
     * Historial de conversaciones
     */
    public function historial()
    {
        $conversaciones = Auth::user()
            ->chatConversaciones()
            ->withCount('mensajes')
            ->with(['ultimoMensaje'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('chat.historial', compact('conversaciones'));
    }

    /**
     * Obtener mensajes (scroll infinito)
     */
    public function obtenerMensajes(Request $request, ChatConversacion $conversacion): JsonResponse
    {
        if ($conversacion->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'error' => 'No tienes acceso'], 403);
        }

        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $mensajes = $conversacion->mensajes()
            ->orderBy('created_at', 'desc')
            ->skip($offset)
            ->take($limit)
            ->get()
            ->reverse();

        return response()->json([
            'success' => true,
            'mensajes' => $mensajes->map(fn($m) => [
                'id' => $m->id,
                'tipo' => $m->tipo,
                'contenido' => $m->contenido,
                'imagenes' => $m->imagenes ? array_map(fn($img) => Storage::url($img), $m->imagenes) : null,
                'created_at' => $m->created_at->format('H:i'),
                'created_at_full' => $m->created_at->format('d/m/Y H:i:s')
            ]),
            'has_more' => $conversacion->mensajes()->count() > ($offset + $limit)
        ]);
    }

    /**
     * Verificar conexión Hugging Face
     */
    public function verificarConexion(): JsonResponse
    {
        if (!Auth::user()->hasRole('administrador')) {
            return response()->json(['success' => false, 'error' => 'No autorizado'], 403);
        }

        $resultado = $this->chatService->verificarConexion();

        return response()->json($resultado);
    }

    /**
     * Método adicional para probar Hugging Face desde admin
     */
    public function probarServicio(): JsonResponse
    {
        if (!Auth::user()->hasRole('administrador')) {
            return response()->json(['success' => false, 'error' => 'No autorizado'], 403);
        }

        try {
            $conversacionPrueba = new ChatConversacion([
                'user_id' => Auth::id(),
                'titulo' => 'Prueba de conexión'
            ]);
            $conversacionPrueba->save();

            $resultado = $this->chatService->enviarMensaje(
                $conversacionPrueba,
                'Hola, esto es una prueba de conexión. Responde brevemente que la conexión funciona correctamente.'
            );

            $conversacionPrueba->mensajes()->delete();
            $conversacionPrueba->delete();

            return response()->json([
                'success' => $resultado['success'],
                'message' => $resultado['success'] ? 'Conexión con Hugging Face exitosa' : 'Error en la conexión',
                'respuesta' => $resultado['respuesta'] ?? null,
                'error' => $resultado['error'] ?? null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al probar conexión: ' . $e->getMessage()
            ]);
        }
    }
}
