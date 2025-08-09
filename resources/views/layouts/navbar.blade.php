<nav class="navbar navbar-expand-md bg-white shadow-sm px-4 w-100 position-relative">
  <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">

    <!-- Logo centrado en móvil, izquierda en md+ -->
    <a class="navbar-brand fw-bold text-success d-flex align-items-center order-1 order-md-1 mx-auto mx-md-0" href="{{ url('/') }}">
      <img src="{{ asset('img/logo-extendido.png') }}" alt="Lanzahostel" height="40" class="me-2" />
    </a>

    <!-- Menú desktop centrado con position absolute -->
    <div class="d-none d-md-block position-absolute start-50 translate-middle-x order-md-2" id="mainNavbar">
      <ul class="navbar-nav flex-row text-center">
        <li class="nav-item mx-2">
          <a class="nav-link text-dark" href="{{ url('/') }}">Inicio</a>
        </li>
        <li class="nav-item mx-2">
          <a class="nav-link text-dark" href="{{ url('/jobs') }}">Puestos</a>
        </li>
        <li class="nav-item mx-2">
          <a class="nav-link text-dark" href="{{ url('/about') }}">Acerca de</a>
        </li>
      </ul>
    </div>

    <!-- Botón y toggler centrados en móvil, derecha en md+ -->
    <div class="d-flex align-items-center order-2 order-md-3 w-100 w-md-auto justify-content-center justify-content-md-end mt-2 mt-md-0">
      <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse"
        data-bs-target="#mobileNavbar" aria-controls="mobileNavbar" aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      @guest
        <a href="{{ route('register') }}" class="btn btn-success">Registrarse</a>
      @else
        <div class="dropdown">
          <button class="btn btn-success dropdown-toggle d-flex align-items-center gap-2" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            @if(Auth::user()->foto_perfil)
              <img src="{{ asset('storage/' . Auth::user()->foto_perfil) }}" alt="Foto de perfil" class="rounded-circle" style="width: 30px; height: 30px; object-fit: cover;">
            @endif
            {{ Auth::user()->name }}
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuButton">
            <li><a class="dropdown-item" href="{{ route('dashboard') }}">Perfil</a></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item">Cerrar sesión</button>
              </form>
            </li>
          </ul>
        </div>
      @endguest

    </div>

  </div>

  <!-- Menú móvil colapsable debajo del toggler + botón -->
  <div class="w-100 d-md-none mt-2">
    <div class="collapse" id="mobileNavbar">
      <ul class="navbar-nav text-center bg-white py-3 mx-auto" style="max-width: 300px;">
        <li class="nav-item">
          <a class="nav-link text-dark" href="{{ url('/') }}">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="{{ url('/jobs') }}">Puestos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="{{ url('/about') }}">Acerca de</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
