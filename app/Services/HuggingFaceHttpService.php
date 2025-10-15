<?php

namespace App\Services;

use App\Models\ChatConversacion;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HuggingFaceHttpService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct($apiKey = null, $baseUrl = null)
    {
        $this->apiKey = $apiKey ?? config('services.huggingface.api_key');
        $this->baseUrl = $baseUrl ?? 'https://api-inference.huggingface.co/models/gpt2';
    }

    public function enviarMensaje(ChatConversacion $conversacion, string $mensaje)
    {
        try {
            // Guardar mensaje del usuario
            $conversacion->mensajes()->create([
                'tipo' => 'usuario',
                'contenido' => $mensaje
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey
            ])->timeout(30)
              ->post($this->baseUrl, [
                  'inputs' => $mensaje
              ]);

            if (!$response->successful()) {
                throw new \Exception('Error de conexión: ' . $response->body());
            }

            $data = $response->json();
            $respuestaIA = $data[0]['generated_text'] ?? ($data['error'] ?? null);

            if (!$respuestaIA) {
                throw new \Exception('Respuesta inválida de Hugging Face');
            }

            // Guardar respuesta del asistente
            $mensajeAsistente = $conversacion->mensajes()->create([
                'tipo' => 'asistente',
                'contenido' => $respuestaIA
            ]);

            return [
                'success' => true,
                'respuesta' => $respuestaIA,
                'mensaje_id' => $mensajeAsistente->id
            ];

        } catch (\Exception $e) {
            Log::error('HuggingFaceHttpService error: ' . $e->getMessage(), [
                'conversacion_id' => $conversacion->id,
                'mensaje' => $mensaje,
            ]);

            return ['success' => false, 'error' => 'Lo siento, hubo un problema al procesar tu consulta.'];
        }
    }

    public function verificarConexion()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey
            ])->timeout(10)
              ->post($this->baseUrl, [
                  'inputs' => 'Prueba de conexión, responde solo: "Conexión exitosa"'
              ]);

            if (!$response->successful()) {
                return ['success' => false, 'error' => 'Error de conexión: ' . $response->body()];
            }

            $data = $response->json();
            $respuesta = $data[0]['generated_text'] ?? 'Conexión exitosa';

            return ['success' => true, 'message' => $respuesta];

        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
