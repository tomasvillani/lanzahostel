<?php

namespace App\Http\Controllers;

use App\Models\Puesto;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SolicitudesController extends Controller
{
    // Cliente: enviar solicitud a un puesto
    public function store(Puesto $puesto)
    {
        $user = Auth::user();

        if ($user->tipo !== 'c') {
            abort(403, 'No autorizado.');
        }

        if (!$user->cv) {
            return redirect()->back()->withErrors(['Debes subir tu CV antes de postularte.']);
        }

        if (Solicitud::where('cliente_id', $user->id)->where('puesto_id', $puesto->id)->exists()) {
            return redirect()->back()->withErrors(['Ya has enviado una solicitud para este puesto.']);
        }

        Solicitud::create([
            'cliente_id' => $user->id,
            'puesto_id' => $puesto->id,
            'estado' => 'p', // pendiente
        ]);

        return redirect()->route('applications.sent')->with('success', 'Solicitud enviada con éxito.');
    }

    // Cliente: ver solicitudes enviadas
    public function sent()
    {
        if (Auth::user()->tipo !== 'c') {
            abort(403, 'Acceso no autorizado.');
        }

        $solicitudes = Solicitud::with('puesto.empresa')
            ->where('cliente_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('applications.sent', compact('solicitudes'));
    }

    // Empresa: ver solicitudes recibidas para sus puestos
    public function received(Puesto $puesto = null)
    {
        $user = Auth::user();

        if ($user->tipo !== 'e') {
            abort(403, 'Acceso no autorizado.');
        }

        if ($puesto) {
            // Validar que el puesto pertenece a la empresa autenticada
            if ($puesto->empresa_id !== $user->id) {
                abort(403, 'No tienes permiso para ver estas solicitudes.');
            }

            $solicitudes = Solicitud::with('cliente', 'puesto')
                ->where('puesto_id', $puesto->id)
                ->latest()
                ->paginate(12);
        } else {
            // Mostrar todas las solicitudes para todos los puestos de la empresa
            $puestosEmpresa = Puesto::where('empresa_id', $user->id)->pluck('id');

            $solicitudes = Solicitud::with('cliente', 'puesto')
                ->whereIn('puesto_id', $puestosEmpresa)
                ->latest()
                ->paginate(12);
        }

        return view('applications.received', compact('solicitudes', 'puesto'));
    }

    // Empresa: aceptar solicitud
    public function accept(Solicitud $solicitud)
    {
        if (Auth::user()->tipo !== 'e') {
            abort(403);
        }

        // Validar que la solicitud pertenece a un puesto de la empresa
        if ($solicitud->puesto->empresa_id !== Auth::id()) {
            abort(403);
        }

        $solicitud->update(['estado' => 'a']); // aceptada

        return back()->with('success', 'Solicitud aceptada.');
    }

    // Empresa: rechazar solicitud
    public function reject(Solicitud $solicitud)
    {
        if (Auth::user()->tipo !== 'e') {
            abort(403);
        }

        // Validar que la solicitud pertenece a un puesto de la empresa
        if ($solicitud->puesto->empresa_id !== Auth::id()) {
            abort(403);
        }

        $solicitud->update(['estado' => 'r']); // rechazada

        return back()->with('success', 'Solicitud rechazada.');
    }

    public function destroy(Solicitud $solicitud)
    {
        // Verificar que el usuario sea cliente
        if (Auth::user()->tipo !== 'c') {
            abort(403, 'No autorizado.');
        }

        // Verificar que la solicitud pertenece al usuario autenticado
        if ($solicitud->cliente_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar esta solicitud.');
        }

        $solicitud->delete();

        return redirect()->route('applications.sent')->with('success', 'Solicitud eliminada correctamente.');
    }

    public function show(Solicitud $solicitud)
    {
        $user = Auth::user();

        // Cliente solo puede ver sus propias solicitudes
        if ($user->tipo === 'c' && $solicitud->cliente_id !== $user->id) {
            abort(403, 'No tienes permiso para ver esta solicitud.');
        }

        // Empresa solo puede ver solicitudes para sus puestos
        if ($user->tipo === 'e' && $solicitud->puesto->empresa_id !== $user->id) {
            abort(403, 'No tienes permiso para ver esta solicitud.');
        }

        // Cargar relaciones necesarias para la vista
        $solicitud->load('puesto.empresa', 'cliente');

        return view('applications.show', compact('solicitud'));
    }

    public function myAcceptedJobs(Request $request)
    {
        $user = $request->user();

        if ($user->tipo !== 'c') {
            abort(403, 'Solo clientes pueden ver sus puestos aceptados.');
        }

        $solicitudesAceptadas = Solicitud::with('puesto.empresa')
            ->where('cliente_id', $user->id)
            ->where('estado', 'a')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('myacceptedjobs.index', compact('solicitudesAceptadas'));
    }

}
