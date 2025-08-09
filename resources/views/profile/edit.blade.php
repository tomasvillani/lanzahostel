@extends('layouts.layout')

@section('title', 'Perfil de Usuario')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">Perfil</h2>

    @if ($errors->has('password'))
        <div class="alert alert-danger">
            La contraseña ingresada es incorrecta. No se eliminó la cuenta.
        </div>
    @endif

    @if(session('status') === 'files-updated')
        <div class="alert alert-success">
            Archivos actualizados correctamente.
        </div>
    @endif

    <div class="row justify-content-center gy-4">
        <div class="col-md-8">

            {{-- Información de perfil --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Información del Perfil</h5>
                    <p class="small text-muted mb-0">Actualiza la información de tu perfil y correo electrónico.</p>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Solo clientes: Subir foto de perfil y CV --}}
            @if(auth()->user()->tipo === 'c')
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Foto de Perfil y CV</h5>
                    <p class="small text-muted mb-0">Sube tu foto de perfil y tu currículum vitae para mejorar tu perfil.</p>
                </div>
                <div class="card-body">
                    @if(auth()->user()->foto_perfil)
                        <div class="mb-3 text-center">
                            <p>Foto de perfil actual:</p>
                            <img src="{{ asset('storage/' . auth()->user()->foto_perfil) }}" alt="Foto de Perfil" class="img-thumbnail" style="max-width: 150px;">
                        </div>
                    @else
                        <p class="text-muted">No tienes una foto de perfil. Sube una para mejorar tu perfil.</p>
                    @endif

                    <form method="POST" action="{{ route('profile.uploadFiles') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="foto_perfil" class="form-label fw-bold">Foto de Perfil</label>
                            <input type="file" class="form-control @error('foto_perfil') is-invalid @enderror" id="foto_perfil" name="foto_perfil" accept="image/*">
                            @error('foto_perfil')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if(auth()->user()->foto_perfil)
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="remove_foto_perfil" name="remove_foto_perfil">
                            <label class="form-check-label" for="remove_foto_perfil">
                                Eliminar foto de perfil actual
                            </label>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="cv" class="form-label fw-bold">Currículum Vitae (CV)</label>
                            <input type="file" class="form-control @error('cv') is-invalid @enderror" id="cv" name="cv" accept=".pdf,.doc,.docx">
                            @error('cv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if(auth()->user()->cv)
                                <div class="mt-2 mb-2">
                                    <p class="mb-1">CV actual:</p>
                                    <a href="{{ asset('storage/' . auth()->user()->cv) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        Ver CV
                                    </a>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="remove_cv" name="remove_cv">
                                    <label class="form-check-label" for="remove_cv">
                                        Eliminar CV actual
                                    </label>
                                </div>
                            @else
                                <p class="text-muted">No tienes un currículum vitae. Sube uno para mejorar tu perfil.</p>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-success">Actualizar Archivos</button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Actualizar contraseña --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Actualizar Contraseña</h5>
                    <p class="small text-muted mb-0">Asegúrate de usar una contraseña larga y segura.</p>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Eliminar cuenta --}}
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-danger">Eliminar Cuenta</h5>
                    <p class="small text-muted mb-0">Una vez eliminada, no podrás recuperar tu cuenta ni datos.</p>
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
