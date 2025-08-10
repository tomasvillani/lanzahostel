@extends('layouts.layout')

@section('title', $puesto->nombre)

@section('content')
<div class="container my-5">

    @if($errors->any())
        <div class="alert alert-danger text-center">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-5">
            <img src="{{ $puesto->imagen ? asset('storage/' . $puesto->imagen) : asset('img/default-puesto.png') }}" 
                alt="{{ $puesto->nombre }}" class="img-fluid rounded">
        </div>
        <div class="col-md-7 d-flex flex-column justify-content-between">
            <div>
                <h2>{{ $puesto->nombre }}</h2>

                <p>
                    <i class="bi bi-buildings"></i>
                    <strong>Empresa:</strong> {{ $puesto->empresa->name }}
                </p>

                <p>
                    <strong>Descripción:</strong><br>
                    {{ $puesto->descripcion ?? 'Sin descripción disponible.' }}
                </p>

                <p>
                    <i class="bi bi-calendar-event"></i>
                    <strong>Fecha de publicación:</strong> {{ $puesto->created_at->format('d/m/Y') }}
                </p>

            </div>

            <div class="mt-4">
                @auth
                    @if (auth()->user()->tipo === 'c') {{-- Cliente --}}

                        {{-- Si no tiene CV --}}
                        @if (!auth()->user()->cv)
                            <div class="alert alert-warning text-center">
                                Debes subir tu CV antes de postularte a este puesto.
                            </div>
                            <a href="{{ route('profile.edit') }}" class="btn btn-secondary btn-lg w-100">Subir CV</a>

                        {{-- Si ya solicitó --}}
                        @elseif ($yaSolicito)
                            <button class="btn btn-secondary btn-lg w-100" disabled>Ya solicitado</button>

                        {{-- Puede postularse --}}
                        @else
                            <form action="{{ route('applications.store',$puesto) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg w-100">¡Me interesa!</button>
                            </form>
                        @endif

                    @elseif (auth()->user()->tipo === 'e') {{-- Empresa --}}
                        @php
                            $usuarioEmpresa = auth()->user();
                            $puestoEmpresa = $puesto->empresa;
                        @endphp

                        @if ($usuarioEmpresa && $puestoEmpresa && $usuarioEmpresa->id === $puestoEmpresa->id)
                            <a href="{{ route('applications.received', $puesto) }}" class="btn btn-secondary btn-lg w-100">
                                Ver interesados
                            </a>
                        @endif
                    @endif

                @else
                    {{-- Invitados: redirigir al login --}}
                    <a href="{{ route('login') }}" class="btn btn-success btn-lg w-100">¡Me interesa!</a>
                @endauth
            </div>

        </div>
    </div>
</div>
@endsection
