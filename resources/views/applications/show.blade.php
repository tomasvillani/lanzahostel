@extends('layouts.layout')

@section('title', 'Detalles de la Solicitud')

@section('content')
<div class="container my-5">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h2>Solicitud enviada</h2>

    <div class="row mb-4">
        <div class="col-md-6">
            <h4>Puesto solicitado</h4>
            <div class="card">
                <img src="{{ $solicitud->puesto->imagen ? asset('storage/' . $solicitud->puesto->imagen) : asset('img/default-puesto.png') }}" class="card-img-top" alt="{{ $solicitud->puesto->nombre }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $solicitud->puesto->nombre }}</h5>
                    <p><strong>Empresa:</strong> {{ $solicitud->puesto->empresa->name }}</p>
                    <p><strong>Descripción:</strong> {{ $solicitud->puesto->descripcion ?? 'Sin descripción disponible.' }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 text-center">
            <h4>Cliente solicitante</h4>
            <img src="{{ $solicitud->cliente->foto_perfil ? asset('storage/' . $solicitud->cliente->foto_perfil) : asset('img/default-user.png') }}" 
                 alt="{{ $solicitud->cliente->name }}" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
            <h5>{{ $solicitud->cliente->name }}</h5>
            <p><i class="bi bi-envelope"></i> {{ $solicitud->cliente->email }}</p>

            @if($solicitud->cliente->telefono)
                <p><i class="bi bi-telephone"></i> {{ $solicitud->cliente->telefono }}</p>
            @endif

            @if($solicitud->cliente->cv)
                <p>
                    <i class="bi bi-file-earmark-person"></i>
                    <a href="{{ asset('storage/' . $solicitud->cliente->cv) }}" target="_blank" rel="noopener noreferrer">Ver CV</a>
                </p>
            @endif
        </div>
    </div>

    <p><strong>Fecha de la solicitud:</strong> {{ $solicitud->created_at->format('d/m/Y') }}</p>
    <p><strong>Estado:</strong> 
        @switch($solicitud->estado)
            @case('p') Pendiente @break
            @case('a') Aceptada @break
            @case('r') Rechazada @break
            @default Desconocido
        @endswitch
    </p>

    @if($solicitud->estado === 'p')
        <div class="mt-4 d-flex gap-3 justify-content-center">
            <form action="{{ route('applications.accept', $solicitud) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres aceptar esta solicitud?');">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success">Aceptar</button>
            </form>

            <form action="{{ route('applications.reject', $solicitud) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres rechazar esta solicitud?');">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-danger">Rechazar</button>
            </form>
        </div>
    @endif

</div>
@endsection
