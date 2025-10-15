@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Crear nuevo dermatólogo</h2>

    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.dermatologos.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nombre completo</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Foto del dermatólogo</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('admin.dermatologos.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection
