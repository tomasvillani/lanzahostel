@extends('layouts.layout')

@section('title', 'Solicitudes Enviadas')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Solicitudes Enviadas</h2>

    @if($success = session('success'))
        <div class="alert alert-success text-center">
            {{ $success }}
        </div>
    @endif

    @if ($solicitudes->count())
        <div class="row">
            @foreach ($solicitudes as $solicitud)
                @php
                    $puesto = $solicitud->puesto;
                @endphp
                <div class="col-12 col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="{{ $puesto->imagen ? asset('storage/' . $puesto->imagen) : asset('img/default-puesto.png') }}" class="card-img-top" alt="{{ $puesto->nombre }}">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title text-center">{{ $puesto->nombre }}</h5>
                                <p><i class="bi bi-buildings"></i> {{ $puesto->empresa->name }}</p>
                                <p><strong>Estado:</strong> 
                                    @switch($solicitud->estado)
                                        @case('p') Pendiente @break
                                        @case('a') Aceptada @break
                                        @case('r') Rechazada @break
                                        @default Desconocido
                                    @endswitch
                                </p>
                            </div>

                            <div class="d-flex justify-content-center gap-2 mt-3">
                                <a href="{{ route('jobs.show', $puesto) }}" class="btn btn-outline-primary btn-sm">Ver detalle</a>

                                @if ($solicitud->estado !== 'a')
                                    <form action="{{ route('applications.destroy', $solicitud) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta solicitud?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Eliminar</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $solicitudes->links() }}
        </div>
    @else
        <p class="text-center fs-5">No has enviado ninguna solicitud aún.</p>
    @endif
</div>
@endsection
