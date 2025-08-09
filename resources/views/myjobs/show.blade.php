@extends('layouts.layout')

@section('title', 'Detalle del Puesto')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">{{ $puesto->nombre }}</h2>

    @if ($puesto->imagen)
        <img src="{{ asset('storage/' . $puesto->imagen) }}" alt="{{ $puesto->nombre }}" class="img-fluid mb-4" style="max-height: 400px; object-fit: cover;">
    @endif

    <div class="mb-3">
        <h5>Descripción</h5>
        <p>{{ $puesto->descripcion }}</p>
    </div>

    <a href="{{ route('myjobs.index') }}" class="btn btn-secondary">Volver a Mis Puestos</a>
    <a href="{{ route('myjobs.edit', $puesto) }}" class="btn btn-warning">Editar Puesto</a>
</div>
@endsection
