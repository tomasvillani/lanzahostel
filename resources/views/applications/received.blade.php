@extends('layouts.layout')

@section('title','Solicitudes Recibidas')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">
        Solicitudes Recibidas
        @if($puesto)
            para: {{ $puesto->nombre }}
        @endif
    </h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($solicitudes->count())
        <div class="row">
            @foreach ($solicitudes as $solicitud)
                @php
                    $puestoActual = $solicitud->puesto;
                    $cliente = $solicitud->cliente;
                @endphp
                <div class="col-12 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="row g-0">
                            <div class="col-6 border-end d-flex flex-column align-items-center justify-content-center p-3 text-center">
                                <h5>Cliente</h5>
                                <img src="{{ $cliente->foto_perfil ? asset('storage/' . $cliente->foto_perfil) : asset('img/default-user.png') }}" 
                                     alt="{{ $cliente->name }}" class="rounded-circle mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                                <p class="mb-1"><i class="bi bi-person-circle"></i> {{ $cliente->name }}</p>

                                @if($cliente->telefono)
                                    <p class="mb-1"><i class="bi bi-telephone"></i> {{ $cliente->telefono }}</p>
                                @endif

                                @if($cliente->cv)
                                    <p><a href="{{ asset('storage/' . $cliente->cv) }}" target="_blank" class="btn btn-outline-primary btn-sm">Ver CV</a></p>
                                @endif
                            </div>
                            <div class="col-6 d-flex flex-column align-items-center justify-content-center p-3 text-center">
                                <h5>Puesto</h5>

                                <img src="{{ $puestoActual->imagen ? asset('storage/' . $puestoActual->imagen) : asset('img/default-puesto.png') }}" 
                                    alt="{{ $puestoActual->nombre }}" class="img-fluid mb-2" style="max-height: 120px; object-fit: contain;">

                                <p class="mb-1"><i class="bi bi-buildings"></i> {{ $puestoActual->empresa->name }}</p>
                                <p class="mb-0">{{ $puestoActual->descripcion ?? 'Sin descripción disponible.' }}</p>

                                <p class="mt-2"><strong>Estado de la solicitud:</strong> 
                                    @switch($solicitud->estado)
                                        @case('p') Pendiente @break
                                        @case('a') Aceptada @break
                                        @case('r') Rechazada @break
                                        @default Desconocido
                                    @endswitch
                                </p>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-center gap-2">
                            <a href="{{ route('applications.show', $solicitud) }}" class="btn btn-outline-primary btn-sm">Ver detalle</a>

                            @if ($solicitud->estado === 'p')
                                <form action="{{ route('applications.accept', $solicitud) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres aceptar esta solicitud?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm">Aceptar</button>
                                </form>

                                <form action="{{ route('applications.reject', $solicitud) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres rechazar esta solicitud?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger btn-sm">Rechazar</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $solicitudes->links() }}
        </div>
    @else
        <p class="text-center fs-5">
            @if($puesto)
                No tienes solicitudes recibidas para este puesto.
            @else
                No tienes solicitudes recibidas por ahora.
            @endif
        </p>
    @endif
</div>
@endsection
