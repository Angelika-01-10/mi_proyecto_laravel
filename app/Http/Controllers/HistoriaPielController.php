<?php

namespace App\Http\Controllers;

use App\Models\HistoriaPiel;
use App\Models\PerfilDermatologo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HistoriaPielController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->hasRole('paciente')) {
            $historias = HistoriaPiel::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
            $dermatologos = User::role('dermatologo')->get();

            return view('historias.index', compact('historias', 'dermatologos'));
        }

        if (Auth::user()->hasRole('dermatologo')) {
            $historias = HistoriaPiel::where('dermatologo_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
            $perfil = Auth::user()->perfilDermatologo;

            return view('historias.dermatologo', compact('historias', 'perfil'));
        }

        return response('Acceso denegado', 403);
    }

    public function create()
    {
        if (!Auth::user()->hasRole('paciente')) {
            return redirect()->route('historias.index')->with('error', 'Solo los pacientes pueden crear historias.');
        }

        $dermatologos = User::role('dermatologo')->get();
        return view('historias.create', compact('dermatologos'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasRole('paciente')) {
            return redirect()->route('historias.index')->with('error', 'Solo los pacientes pueden crear historias.');
        }

        $request->validate([
            'descripcion_sintomas' => 'required|string',
            'foto_perfil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'documento_medico' => 'nullable|file|mimes:pdf,docx,png,jpg|max:4096',
            'dermatologo_id' => 'nullable|exists:users,id',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'descripcion_sintomas' => $request->descripcion_sintomas,
            'estado' => 'pendiente',
        ];

        if ($request->hasFile('foto_perfil')) {
            $data['foto_perfil'] = $request->file('foto_perfil')->store('fotos_perfil', 'public');
        }

        if ($request->hasFile('documento_medico')) {
            $data['documento_medico'] = $request->file('documento_medico')->store('documentos_medicos', 'public');
        }

        if ($request->dermatologo_id) {
            $data['dermatologo_id'] = $request->dermatologo_id;
            $data['estado'] = 'en revision';
        }

        HistoriaPiel::create($data);

        return redirect()
            ->route('historias.index')
            ->with('success', 'Tu historia de piel fue registrada correctamente.');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        if (!Auth::user()->hasRole('paciente')) {
            return redirect()->route('historias.index')->with('error', 'No tienes permiso para editar esta historia.');
        }

        $historia = HistoriaPiel::findOrFail($id);

        // Verificar que la historia pertenece al usuario
        if ($historia->user_id != Auth::id()) {
            return redirect()->route('historias.index')->with('error', 'No tienes permiso para editar esta historia.');
        }

        // No permitir edición si ya está cerrada
        if ($historia->estado === 'cerrada') {
            return back()->with('error', 'No puedes editar una historia cerrada.');
        }

        $dermatologos = User::role('dermatologo')->get();
        return view('historias.edit', compact('historia', 'dermatologos'));
    }

    /**
     * Actualizar historia
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasRole('paciente')) {
            return redirect()->route('historias.index')->with('error', 'No tienes permiso para editar esta historia.');
        }

        $historia = HistoriaPiel::findOrFail($id);

        // Verificar que la historia pertenece al usuario
        if ($historia->user_id != Auth::id()) {
            return redirect()->route('historias.index')->with('error', 'No tienes permiso para editar esta historia.');
        }

        // No permitir edición si ya está cerrada
        if ($historia->estado === 'cerrada') {
            return back()->with('error', 'No puedes editar una historia cerrada.');
        }

        $request->validate([
            'descripcion_sintomas' => 'required|string',
            'foto_perfil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'documento_medico' => 'nullable|file|mimes:pdf,docx,png,jpg|max:4096',
            'dermatologo_id' => 'nullable|exists:users,id',
        ]);

        $data = [
            'descripcion_sintomas' => $request->descripcion_sintomas,
        ];

        // Manejar foto de perfil
        if ($request->hasFile('foto_perfil')) {
            // Eliminar foto anterior si existe
            if ($historia->foto_perfil) {
                Storage::disk('public')->delete($historia->foto_perfil);
            }
            $data['foto_perfil'] = $request->file('foto_perfil')->store('fotos_perfil', 'public');
        }

        // Manejar documento médico
        if ($request->hasFile('documento_medico')) {
            // Eliminar documento anterior si existe
            if ($historia->documento_medico) {
                Storage::disk('public')->delete($historia->documento_medico);
            }
            $data['documento_medico'] = $request->file('documento_medico')->store('documentos_medicos', 'public');
        }

        // Actualizar dermatólogo si cambió
        if ($request->has('dermatologo_id')) {
            $data['dermatologo_id'] = $request->dermatologo_id;
            if ($request->dermatologo_id && $historia->estado === 'pendiente') {
                $data['estado'] = 'en revision';
            }
        }

        $historia->update($data);

        return redirect()
            ->route('historias.index')
            ->with('success', 'Historia actualizada correctamente.');
    }

    /**
     * Eliminar historia
     */
    public function destroy($id)
    {
        if (!Auth::user()->hasRole('paciente')) {
            return redirect()->route('historias.index')->with('error', 'No tienes permiso para eliminar esta historia.');
        }

        $historia = HistoriaPiel::findOrFail($id);

        // Verificar que la historia pertenece al usuario
        if ($historia->user_id != Auth::id()) {
            return redirect()->route('historias.index')->with('error', 'No tienes permiso para eliminar esta historia.');
        }

        // Opcional: no permitir eliminación si ya está cerrada
        if ($historia->estado === 'cerrada') {
            return back()->with('error', 'No puedes eliminar una historia cerrada.');
        }

        // Eliminar archivos asociados
        if ($historia->foto_perfil) {
            Storage::disk('public')->delete($historia->foto_perfil);
        }
        if ($historia->documento_medico) {
            Storage::disk('public')->delete($historia->documento_medico);
        }

        $historia->delete();

        return redirect()
            ->route('historias.index')
            ->with('success', 'Historia eliminada correctamente.');
    }

    /**
     * Asignar la última historia del paciente a un dermatólogo
     */
    public function asignar($dermatologoId)
    {
        if (!Auth::user()->hasRole('paciente')) {
            return back()->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $dermatologo = User::role('dermatologo')->find($dermatologoId);
        if (!$dermatologo) {
            return back()->with('error', 'Dermatólogo no encontrado.');
        }

        $historia = HistoriaPiel::where('user_id', Auth::id())
            ->whereNull('dermatologo_id')
            ->latest()
            ->first();

        if (!$historia) {
            return back()->with('error', 'No tienes historias pendientes para asignar.');
        }

        $historia->dermatologo_id = $dermatologoId;
        $historia->estado = 'en revision';
        $historia->save();

        return back()->with('success', "Historia enviada al Dr(a). {$dermatologo->name}");
    }

    /**
     * Responder una historia (solo dermatólogos)
     */
    public function responder(Request $request, $id)
    {
        if (!Auth::user()->hasRole('dermatologo')) {
            return response('Acceso denegado', 403);
        }

        $request->validate([
            'respuesta_diagnostico' => 'required|string|min:10',
        ]);

        $historia = HistoriaPiel::findOrFail($id);

        if ($historia->dermatologo_id != Auth::id()) {
            return back()->with('error', 'Esta historia no está asignada a ti.');
        }

        if ($historia->estado === 'cerrada') {
            return back()->with('error', 'Esta historia ya fue cerrada y no puede modificarse.');
        }

        $historia->respuesta_diagnostico = $request->respuesta_diagnostico;
        $historia->estado = 'cerrada';
        $historia->save();

        return back()->with('success', 'Diagnóstico guardado correctamente.');
    }

    /**
     * Mostrar formulario para crear/editar perfil del dermatólogo
     */
    public function crearPerfil()
    {
        if (!Auth::user()->hasRole('dermatologo')) {
            return response('Acceso denegado', 403);
        }

        $perfil = Auth::user()->perfilDermatologo;
        return view('historias.perfil-dermatologo', compact('perfil'));
    }

    /**
     * Guardar o actualizar el perfil del dermatólogo
     */
    public function guardarPerfil(Request $request)
    {
        if (!Auth::user()->hasRole('dermatologo')) {
            return response('Acceso denegado', 403);
        }

        $request->validate([
            'especialidad' => 'nullable|string|max:255',
            'titulo_profesional' => 'nullable|string|max:255',
            'numero_licencia' => 'nullable|string|max:100',
            'biografia' => 'nullable|string',
            'foto_perfil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'curriculum' => 'nullable|file|mimes:pdf|max:4096',
            'años_experiencia' => 'nullable|integer|min:0',
            'telefono' => 'nullable|string|max:20',
        ]);

        $data = $request->only([
            'especialidad',
            'titulo_profesional',
            'numero_licencia',
            'biografia',
            'años_experiencia',
            'telefono'
        ]);

        if ($request->hasFile('foto_perfil')) {
            $data['foto_perfil'] = $request->file('foto_perfil')
                ->store('perfiles_dermatologos', 'public');
        }

        if ($request->hasFile('curriculum')) {
            $data['curriculum'] = $request->file('curriculum')
                ->store('curriculums', 'public');
        }

        $perfil = Auth::user()->perfilDermatologo;

        if ($perfil) {
            $perfil->update($data);
        } else {
            $data['user_id'] = Auth::id();
            PerfilDermatologo::create($data);
        }

        return redirect()
            ->route('historias.index')
            ->with('success', 'Perfil profesional actualizado correctamente.');
    }

    public function verPerfilDermatologo($id)
{
    $dermatologo = User::with('perfilDermatologo')->findOrFail($id);

    if (!$dermatologo->hasRole('dermatologo')) {
        return back()->with('error', 'El usuario seleccionado no es un dermatólogo.');
    }

    return view('historias.ver-perfil-dermatologo', compact('dermatologo'));
}

}