<?php

namespace App\Http\Controllers;

use App\Models\Puesto;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PuestosController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->tipo !== 'e') {
            abort(403, 'Solo empresas pueden ver sus puestos.');
        }

        $puestos = Puesto::where('empresa_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('myjobs.index', compact('puestos'));
    }

    public function create()
    {
        if (Auth::user()->tipo !== 'e') {
            abort(403, 'Solo empresas pueden crear puestos.');
        }
        return view('myjobs.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->tipo !== 'e') {
            abort(403, 'Solo empresas pueden crear puestos.');
        }

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagen' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('puestos', 'public');
        }

        $data['empresa_id'] = Auth::id();

        Puesto::create($data);

        return redirect()->route('myjobs.index')->with('success', 'Puesto publicado correctamente.');
    }

    public function show(Puesto $puesto)
    {
        $yaSolicito = false;

        if (Auth::check() && Auth::user()->tipo === 'c') {
            $yaSolicito = Solicitud::where('cliente_id', Auth::id())
                ->where('puesto_id', $puesto->id)
                ->exists();
        }

        return view('jobs.show', compact('puesto', 'yaSolicito'));
    }

    public function edit(Puesto $puesto)
    {
        if ($puesto->empresa_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar este puesto.');
        }

        return view('myjobs.edit', compact('puesto'));
    }

    public function update(Request $request, Puesto $puesto)
    {
        if ($puesto->empresa_id !== Auth::id()) {
            abort(403, 'No tienes permiso para actualizar este puesto.');
        }

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagen' => 'nullable|image|max:2048',
            'eliminar_imagen' => 'nullable|boolean',
        ]);

        // Eliminar imagen si se solicitó
        if ($request->boolean('eliminar_imagen')) {
            if ($puesto->imagen && Storage::disk('public')->exists($puesto->imagen)) {
                Storage::disk('public')->delete($puesto->imagen);
            }
            $data['imagen'] = null;
        }

        // Subir nueva imagen
        if ($request->hasFile('imagen')) {
            if ($puesto->imagen && Storage::disk('public')->exists($puesto->imagen)) {
                Storage::disk('public')->delete($puesto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('puestos', 'public');
        }

        $puesto->update($data);

        return redirect()->route('myjobs.index')->with('success', 'Puesto actualizado correctamente.');
    }

    public function destroy(Puesto $puesto)
    {
        if ($puesto->empresa_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar este puesto.');
        }

        if ($puesto->imagen && Storage::disk('public')->exists($puesto->imagen)) {
            Storage::disk('public')->delete($puesto->imagen);
        }

        $puesto->delete();

        return redirect()->route('myjobs.index')->with('success', 'Puesto eliminado correctamente.');
    }

    public function all(Request $request)
    {
        $query = $request->input('q');

        $puestos = Puesto::with('empresa')
            ->when($query, function ($q) use ($query) {
                $q->where('nombre', 'like', "%{$query}%")
                  ->orWhere('descripcion', 'like', "%{$query}%")
                  ->orWhereHas('empresa', function ($q) use ($query) {
                      $q->where('name', 'like', "%{$query}%");
                  });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $hasFilter = !empty($query);

        return view('jobs.index', compact('puestos', 'query', 'hasFilter'));
    }
}
