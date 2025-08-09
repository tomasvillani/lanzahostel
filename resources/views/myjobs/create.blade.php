@extends('layouts.layout')

@section('title', 'Publicar Nuevo Puesto')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Publicar Nuevo Puesto</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('myjobs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Puesto</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="5" required>{{ old('descripcion') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen del Puesto (opcional)</label>
            <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Publicar Puesto</button>
        <a href="{{ route('myjobs.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
