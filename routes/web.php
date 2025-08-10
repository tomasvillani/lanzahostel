<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PuestosController;
use App\Http\Controllers\SolicitudesController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/jobs', [PuestosController::class, 'all'])->name('jobs.index');

Route::get('/jobs/{puesto}', [PuestosController::class, 'show'])
    ->name('jobs.show');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/upload-files', [ProfileController::class, 'uploadFiles'])->name('profile.uploadFiles');
    Route::resource('myjobs', PuestosController::class)->parameters([
        'myjobs' => 'puesto'
    ])->except(['show']);

    // Cliente -> enviar solicitud
    Route::post('/jobs/{puesto}/apply', [SolicitudesController::class, 'store'])
    ->name('applications.store');

    // Cliente -> solicitudes propuestas
    Route::get('/applications/sent', [SolicitudesController::class, 'sent'])
        ->name('applications.sent');

    // Empresa -> aceptar / rechazar solicitud
    Route::patch('/applications/{solicitud}/accept', [SolicitudesController::class, 'accept'])
        ->name('applications.accept');
    Route::patch('/applications/{solicitud}/reject', [SolicitudesController::class, 'reject'])
        ->name('applications.reject');

    // Ver solicitudes recibidas (empresa)
    Route::get('/applications/received/{puesto?}', [SolicitudesController::class, 'received'])
        ->name('applications.received');

    Route::delete('/applications/{solicitud}', [SolicitudesController::class, 'destroy'])->name('applications.destroy');

    Route::get('/applications/{solicitud}', [SolicitudesController::class, 'show'])
        ->name('applications.show');

    Route::get('/myacceptedjobs', [SolicitudesController::class, 'myAcceptedJobs'])->name('myacceptedjobs.index');
});

require __DIR__.'/auth.php';
