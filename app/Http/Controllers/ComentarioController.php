<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 📌 Editar comentario (formulario)
    public function edit(Comentario $comentario)
    {
        // Solo el dueño del comentario o un dermatólogo puede editar
        $this->authorize('update', $comentario);
        return view('comentarios.edit', compact('comentario'));
    }

    // 📌 Actualizar comentario
    public function update(Request $request, Comentario $comentario)
    {
        $this->authorize('update', $comentario);

        $request->validate([
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $path = $comentario->imagen;
        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('comentarios', 'public');
        }

        $comentario->update([
            'contenido' => $request->contenido,
            'imagen' => $path,
        ]);

        return redirect()->route('foros.show', $comentario->foro_id)
                         ->with('success', 'Comentario actualizado correctamente');
    }

    // 📌 Crear comentario
public function store(Request $request, $foroId)
{
    $request->validate([
        'contenido' => 'required|string',
        'imagen' => 'nullable|image|max:2048',
    ]);

    $path = null;
    if ($request->hasFile('imagen')) {
        $path = $request->file('imagen')->store('comentarios', 'public');
    }

    Comentario::create([
        'foro_id' => $foroId,
        'user_id' => auth()->id(),
        'contenido' => $request->contenido,
        'imagen' => $path,
        'verificado' => false,
    ]);

    return redirect()->back()->with('success', 'Comentario publicado correctamente');
}


    // 📌 Eliminar comentario
    public function destroy(Comentario $comentario)
    {
        $this->authorize('delete', $comentario);

        $foroId = $comentario->foro_id;
        $comentario->delete();

        return redirect()->route('foros.show', $foroId)
                         ->with('success', 'Comentario eliminado correctamente');
    }
}
