@extends('layouts.layout')

@section('title', 'Mis Puestos Aceptados')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Mis Puestos Aceptados</h2>

    @if ($solicitudesAceptadas->isEmpty())
        <div class="alert alert-info">No tienes puestos aceptados aún.</div>
    @else
        <div class="row">
            @foreach ($solicitudesAceptadas as $solicitud)
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        @if($solicitud->puesto->imagen)
                            <img src="{{ asset('storage/' . $solicitud->puesto->imagen) }}" class="card-img-top" alt="{{ $solicitud->puesto->nombre }}">
                        @else
                            <img src="{{ asset('img/default-puesto.png') }}" class="card-img-top" alt="Imagen por defecto">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $solicitud->puesto->nombre }}</h5>
                            <p class="card-text"><strong>Empresa:</strong> {{ $solicitud->puesto->empresa->name }}</p>
                            <p class="card-text">{{ Str::limit($solicitud->puesto->descripcion, 100) }}</p>
                            <a href="{{ route('jobs.show', $solicitud->puesto) }}" class="btn btn-success mt-auto">Ver Detalles</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $solicitudesAceptadas->links() }}
    @endif
</div>
@endsection
