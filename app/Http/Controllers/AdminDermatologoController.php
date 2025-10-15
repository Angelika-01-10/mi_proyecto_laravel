<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminDermatologoController extends Controller
{
    public function index()
    {
        $dermatologos = User::role('dermatologo')->get();
        return view('admin.dermatologos.index', compact('dermatologos'));
    }

    public function create()
    {
        return view('admin.dermatologos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'email']);
        $data['password'] = bcrypt($request->password);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('perfiles_dermatologos', 'public');
        }

        $dermatologo = User::create($data);

        $dermatologo->assignRole('dermatologo');

        return redirect()->route('admin.dermatologos.index')->with('success', 'Dermatólogo creado correctamente');
    }

    public function destroy($id)
    {
        $dermatologo = User::findOrFail($id);
        $dermatologo->delete();

        return redirect()->route('admin.dermatologos.index')->with('success', 'Dermatólogo eliminado correctamente');
    }
}
