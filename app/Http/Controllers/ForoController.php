<?php

namespace App\Http\Controllers;

use App\Models\Foro;
use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ForoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 📌 Listar todos los foros
    public function index()
    {
        $foros = Foro::latest()->get();
        return view('foros.index', compact('foros'));
    }

    // 📌 Mostrar formulario de creación
    public function create()
    {
        // Todos los usuarios pueden crear
        return view('foros.create');
    }

    // 📌 Guardar foro nuevo
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|max:2048', // nueva validación
        ]);

        $data = [
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'user_id' => auth()->id(),
        ];

        // Subir imagen si existe
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('foros', 'public');
        }

        Foro::create($data);

        return redirect()->route('foros.index')
                         ->with('success', 'Foro creado correctamente');
    }

    // 📌 Ver foro con comentarios
    public function show(Foro $foro)
    {
        $foro->load('comentarios.user');
        return view('foros.show', compact('foro'));
    }

    // 📌 Comentar en el foro (paciente o dermatólogo)
    public function comentar(Request $request, Foro $foro)
    {
        $request->validate([
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('comentarios', 'public');
        }

        $foro->comentarios()->create([
            'user_id' => auth()->id(),
            'contenido' => $request->contenido,
            'imagen' => $path,
            'verificado' => auth()->user()->hasRole('dermatologo'),
        ]);

        return back()->with('success', 'Comentario agregado correctamente');
    }

    // 📌 Mostrar formulario de edición
    public function edit(Foro $foro)
    {
        $this->authorize('update', $foro);
        return view('foros.edit', compact('foro'));
    }

    // 📌 Actualizar foro
    public function update(Request $request, Foro $foro)
    {
        $this->authorize('update', $foro);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('titulo', 'contenido');

        // Subir nueva imagen si se envía
        if ($request->hasFile('imagen')) {
            // Eliminar la anterior
            if ($foro->imagen) {
                Storage::disk('public')->delete($foro->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('foros', 'public');
        }

        $foro->update($data);

        return redirect()->route('foros.index')
                         ->with('success', 'Foro actualizado correctamente');
    }

    // 📌 Eliminar foro
    public function destroy(Foro $foro)
    {
        $this->authorize('delete', $foro);

        // Eliminar imagen del foro si existe
        if ($foro->imagen) {
            Storage::disk('public')->delete($foro->imagen);
        }

        // Opcional: eliminar comentarios asociados
        $foro->comentarios()->delete();

        $foro->delete();

        return redirect()->route('foros.index')
                         ->with('success', 'Foro eliminado correctamente');
    }
}
