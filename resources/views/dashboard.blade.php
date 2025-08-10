@extends('layouts.layout')

@section('title', 'Panel de Usuario')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">

            <div class="card shadow border-0">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0">Bienvenido al Panel</h4>
                </div>
                <div class="card-body text-center">
                    <h5 class="card-title">¡Hola, {{ Auth::user()->name }}!</h5>
                    <p class="card-text">Has iniciado sesión correctamente.</p>

                    <div class="d-grid gap-2 d-md-flex justify-content-center mt-4 flex-wrap">
                        {{-- Explorar puestos (todos) --}}
                        <a href="{{ url('/jobs') }}" class="btn btn-outline-primary me-md-2 mb-2">Explorar Puestos</a>

                        {{-- Mis puestos --}}
                        @if(Auth::user()->tipo === 'e')
                            <a href="{{ url('/myjobs') }}" class="btn btn-outline-secondary me-md-2 mb-2">Mis Puestos Publicados</a>
                        @elseif(Auth::user()->tipo === 'c')
                            <a href="{{ url('/myacceptedjobs') }}" class="btn btn-outline-secondary me-md-2 mb-2">Mis Puestos Aceptados</a>
                        @endif

                        {{-- Solicitudes propuestas (solo cliente) --}}
                        @if(Auth::user()->tipo === 'c')
                            <a href="{{ url('/applications/sent') }}" class="btn btn-outline-success me-md-2 mb-2">Solicitudes Propuestas</a>
                        @endif

                        {{-- Solicitudes recibidas (solo empresa) --}}
                        @if(Auth::user()->tipo === 'e')
                            <a href="{{ url('/applications/received') }}" class="btn btn-outline-warning me-md-2 mb-2">Solicitudes Recibidas</a>
                        @endif

                        {{-- Perfil (todos) --}}
                        <a href="{{ url('/profile') }}" class="btn btn-outline-info me-md-2 mb-2">Perfil</a>

                        {{-- Cerrar sesión (todos) --}}
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger mb-2">Cerrar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <small class="text-muted">¿No ves lo que buscas? Revisa el menú o contacta con el soporte.</small>
            </div>
        </div>
    </div>
</div>
@endsection
