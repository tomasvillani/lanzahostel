@extends('layouts.layout')

@section('title', 'Editar Puesto')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Editar Puesto: {{ $puesto->nombre }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('myjobs.update', $puesto) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Puesto</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $puesto->nombre) }}" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="5" required>{{ old('descripcion', $puesto->descripcion) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen del Puesto (opcional)</label>
            @if ($puesto->imagen)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $puesto->imagen) }}" alt="{{ $puesto->nombre }}" style="max-height: 200px; object-fit: cover;">
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="eliminar_imagen" id="eliminar_imagen" value="1">
                    <label class="form-check-label" for="eliminar_imagen">
                        Eliminar imagen actual
                    </label>
                </div>
            @endif
            <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Puesto</button>
        <a href="{{ route('myjobs.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
