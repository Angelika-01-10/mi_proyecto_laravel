@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Lista de dermatólogos</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.dermatologos.create') }}" class="btn btn-primary mb-3">➕ Crear nuevo dermatólogo</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dermatologos as $derma)
                <tr>
                    <td>
                        @if($derma->foto)
                            <img src="{{ asset('storage/' . $derma->foto) }}" alt="Foto" width="50" class="rounded">
                        @else
                            <span>No hay foto</span>
                        @endif
                    </td>
                    <td>{{ $derma->name }}</td>
                    <td>{{ $derma->email }}</td>
                    <td>
                        <form action="{{ route('admin.dermatologos.destroy', $derma->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este dermatólogo?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
