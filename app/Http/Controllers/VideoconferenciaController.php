<?php

namespace App\Http\Controllers;

use App\Models\Videoconferencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Notifications\VideoconferenciaInicioNotification; 

class VideoconferenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Listar todas las videoconferencias
    public function index()
    {
        $videoconferencias = Videoconferencia::with('dermatologo')->get();
        return view('videoconferencias.index', compact('videoconferencias'));
    }

    // Formulario para crear
    public function create()
    {
        return view('videoconferencias.create');
    }

    // Guardar nueva videoconferencia
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'link' => 'required|url',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // nuevo
        ]);

        $data = $request->only('titulo', 'descripcion', 'fecha', 'link');
        $data['dermatologo_id'] = auth()->id();

        // Subir imagen si existe
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('videoconferencias', 'public');
        }

        Videoconferencia::create($data);

        return redirect()->route('videoconferencias.index')
                         ->with('success', 'Videoconferencia creada correctamente');
    }

    // Mostrar detalle de una videoconferencia
    public function show(Videoconferencia $videoconferencia)
    {
        return view('videoconferencias.show', compact('videoconferencia'));
    }

    // Formulario para editar videoconferencia
    public function edit(Videoconferencia $videoconferencia)
    {
        return view('videoconferencias.edit', compact('videoconferencia'));
    }

    // Actualizar videoconferencia
    public function update(Request $request, Videoconferencia $videoconferencia)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'link' => 'required|url',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $data = $request->only('titulo', 'descripcion', 'fecha', 'link');

        // Subir imagen si existe y reemplazar anterior
        if ($request->hasFile('imagen')) {
            if ($videoconferencia->imagen) {
                Storage::disk('public')->delete($videoconferencia->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('videoconferencias', 'public');
        }

        $videoconferencia->update($data);

        return redirect()->route('videoconferencias.index')
                         ->with('success', 'Videoconferencia actualizada correctamente');
    }

    // Eliminar videoconferencia
    public function destroy(Videoconferencia $videoconferencia)
    {
        if ($videoconferencia->imagen) {
            Storage::disk('public')->delete($videoconferencia->imagen);
        }
        $videoconferencia->delete();

        return redirect()->route('videoconferencias.index')
                         ->with('success', 'Videoconferencia eliminada correctamente');
    }

    public function notificar(Videoconferencia $videoconferencia)
{
    foreach ($videoconferencia->pacientes as $paciente) {
        $paciente->notify(new VideoconferenciaInicioNotification($videoconferencia));
    }

    return back()->with('success', 'Se notificó a los pacientes que la videoconferencia está por iniciar.');
}


    // Paciente se inscribe
public function inscribirse(Videoconferencia $videoconferencia)
{
    if (auth()->user()->hasRole('paciente')) {
        $userId = auth()->id();

        // Verificar si ya está inscrito
        if ($videoconferencia->pacientes()->where('user_id', $userId)->exists()) {
            return back()->with('error', '⚠️ Ya estás inscrito en esta videoconferencia.');
        }

        // Inscribir al paciente
        $videoconferencia->pacientes()->attach($userId);

        return back()->with('success', '🎉 Reserva exitosa. ¡Nos vemos en la videoconferencia!');
    }

    return back()->with('error', '🚫 Solo los pacientes pueden inscribirse.');
}

}
