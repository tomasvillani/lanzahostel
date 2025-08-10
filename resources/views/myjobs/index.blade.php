@extends('layouts.layout')

@section('title', 'Mis Puestos')

@section('content')
<div class="container my-5">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Mis Puestos Publicados</h2>
        <a href="{{ route('myjobs.create') }}" class="btn btn-success">Publicar Nuevo Puesto</a>
    </div>

    @if ($puestos->count() > 0)
        <div class="row">
            @foreach ($puestos as $puesto)
                <div class="col-12 col-md-3 mb-4">
                    <div class="card h-100">

                        @if ($puesto->imagen)
                            <img src="{{ asset('storage/' . $puesto->imagen) }}" class="card-img-top" alt="{{ $puesto->nombre }}" style="object-fit: cover; height: 250px;">
                        @else
                            <img src="{{ asset('img/default-job.png') }}" class="card-img-top" alt="Sin imagen" style="object-fit: cover; height: 250px;">
                        @endif

                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title text-center">{{ $puesto->nombre }}</h5>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <a href="{{ route('jobs.show', $puesto) }}" class="btn btn-outline-primary btn-sm w-100">Ver más</a>

                                <a href="{{ route('myjobs.edit', $puesto) }}" class="btn btn-outline-warning btn-sm">Editar</a>

                                <form action="{{ route('myjobs.destroy', $puesto) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este puesto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Eliminar</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $puestos->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>

    @else
        <p class="text-center fs-5 mt-5">No has publicado puestos todavía.</p>
        <div class="text-center">
            <a href="{{ route('myjobs.create') }}" class="btn btn-success">Publicar tu primer puesto</a>
        </div>
    @endif

</div>
@endsection
