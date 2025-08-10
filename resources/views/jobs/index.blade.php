@extends('layouts.layout')

@section('title', 'Puestos')

@section('content')
  <div class="container mt-4 mb-5">
    <form id="searchForm" action="{{ url('/jobs') }}" method="GET" class="d-flex justify-content-center">
      <input id="searchInput" class="form-control me-2" type="search" name="q" placeholder="Buscar por nombre o empresa" aria-label="Buscar" style="max-width: 500px;" value="{{ old('q', $query) }}">
      <button class="btn btn-success" type="submit">Buscar</button>
    </form>
  </div>

  @if($errors->any())
    <div class="alert alert-danger text-center">
      @foreach ($errors->all() as $error)
          <div>{{ $error }}</div>
      @endforeach
    </div>
  @endif

  @if ($puestos->count() > 0)
    <div class="container">
      <div class="row">
        @foreach ($puestos as $puesto)
          <div class="col-6 col-md-3 mb-4">
            <div class="card h-100 d-flex flex-column">
              <div style="height: 180px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                <img src="{{ $puesto->imagen ? asset('storage/' . $puesto->imagen) : asset('img/default-puesto.png') }}" 
                     alt="{{ $puesto->nombre }}" 
                     style="max-height: 100%; max-width: 100%; object-fit: cover; width: 100%;">
              </div>
              <div class="card-body d-flex flex-column">
                <h5 class="card-title text-center">{{ $puesto->nombre }}</h5>
                <p class="card-text mb-2 text-center">
                  <i class="bi bi-buildings"></i>
                  {{ $puesto->empresa->name }}
                </p>
                <p class="card-text mb-3 text-center text-muted" style="font-size: 0.9rem;">
                  <i class="bi bi-calendar-event"></i>
                  {{ $puesto->created_at->format('d/m/Y') }}
                </p>
                <div class="mt-auto text-center">
                  <a href="{{ route('jobs.show', $puesto) }}" class="btn btn-outline-success btn-sm">Ver más</a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="d-flex justify-content-center mt-4">
        {{ $puestos->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
      </div>
    </div>
  @else
    <div class="container mt-5">
      @if ($hasFilter)
        <p class="text-center fs-4">No se encontraron puestos publicados para esta búsqueda.</p>
      @else
        <p class="text-center fs-4">No se encontraron puestos publicados.</p>
      @endif
    </div>
  @endif

  <script>
    document.getElementById('searchForm').addEventListener('submit', function(e) {
      const query = document.getElementById('searchInput').value.trim();
      if (!query) {
        e.preventDefault();
      }
    });
  </script>
@endsection
