@extends('layouts.layout')

@section('content')
<div id="hosteleriaCarousel" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#hosteleriaCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#hosteleriaCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#hosteleriaCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>

  <div class="carousel-inner">

    <div class="carousel-item active">
      <img src="{{ asset('img/lanzahostel_carrusel_1.jpg') }}" class="d-block w-100" alt="Trabajo en Hostelería">
      <div class="carousel-caption bg-dark bg-opacity-50 rounded px-3 py-2 text-center text-white"
           style="bottom: 20px;">
        <h5 class="fs-5 fs-md-4">Encuentra tu oportunidad en Lanzarote</h5>
        <p class="mb-2 fs-6 fs-md-5">Accede a las mejores ofertas de empleo en hostelería cerca de ti.</p>
      </div>
    </div>

    <div class="carousel-item">
      <img src="{{ asset('img/lanzahostel_carrusel_2.jpg') }}" class="d-block w-100" alt="Equipo de trabajo">
      <div class="carousel-caption bg-dark bg-opacity-50 rounded px-3 py-2 text-center text-white"
           style="bottom: 20px;">
        <h5 class="fs-5 fs-md-4">Conecta con empresas locales</h5>
        <p class="mb-2 fs-6 fs-md-5">Las mejores empresas buscan talento como tú para sus equipos.</p>
      </div>
    </div>

    <div class="carousel-item">
      <img src="{{ asset('img/lanzahostel_carrusel_3.jpg') }}" class="d-block w-100" alt="Ambiente de trabajo">
      <div class="carousel-caption bg-dark bg-opacity-50 rounded px-3 py-2 text-center text-white"
           style="bottom: 20px;">
        <h5 class="fs-5 fs-md-4">Impulsa tu futuro en la hostelería</h5>
        <p class="mb-2 fs-6 fs-md-5">Descubre nuevas oportunidades y crece profesionalmente en Lanzarote.</p>
      </div>
    </div>

  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#hosteleriaCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Anterior</span>
  </button>

  <button class="carousel-control-next" type="button" data-bs-target="#hosteleriaCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Siguiente</span>
  </button>
</div>

<div class="container mt-4 mb-5">
<form id="searchForm" action="{{ url('/jobs') }}" method="GET" class="d-flex justify-content-center">
    <input id="searchInput" class="form-control me-2" type="search" name="q" placeholder="Buscar por nombre o empresa" aria-label="Buscar" style="max-width: 500px;">
    <button class="btn btn-success" type="submit">Buscar</button>
</form>
</div>

<h1 class="mt-5 text-center">Bienvenido a Lanzahostel</h1>
<p class="text-center">Tu puerta a las mejores oportunidades de empleo en hostelería en Lanzarote.</p>

<script>
    document.getElementById('searchForm').addEventListener('submit', function(e) {
      const query = document.getElementById('searchInput').value.trim();
      if (!query) {
        e.preventDefault();
      }
    });
  </script>
@endsection
