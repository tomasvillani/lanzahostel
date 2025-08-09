<?php

namespace App\Http\Controllers;

use App\Models\Puesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PuestosController extends Controller
{
    // Mostrar todos los puestos que la empresa actual ha publicado
    public function index(Request $request)
    {
        $empresa = $request->user();

        $puestos = Puesto::where('empresa_id', $empresa->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('myjobs.index', compact('puestos'));
    }

    // Mostrar el formulario para crear un nuevo puesto
    public function create()
    {
        return view('myjobs.create');
    }

    // Guardar un nuevo puesto en la base de datos
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagen' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('puestos', 'public');
        }

        // Asignar siempre el ID de la empresa logueada
        $data['empresa_id'] = Auth::id();

        Puesto::create($data);

        return redirect()->route('myjobs.index')->with('success', 'Puesto publicado correctamente.');
    }

    // Mostrar un puesto específico
    public function show(Puesto $puesto)
    {
        if ($puesto->empresa_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver este puesto.');
        }

        return view('myjobs.show', compact('puesto'));
    }

    // Mostrar el formulario para editar un puesto
    public function edit(Puesto $puesto)
    {
        if ($puesto->empresa_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar este puesto.');
        }

        return view('myjobs.edit', compact('puesto'));
    }

    // Actualizar un puesto existente
    public function update(Request $request, Puesto $puesto)
    {
        if ($puesto->empresa_id !== Auth::id()) {
            abort(403, 'No tienes permiso para actualizar este puesto.');
        }

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagen' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            if ($puesto->imagen && Storage::exists('public/' . $puesto->imagen)) {
                Storage::delete('public/' . $puesto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('puestos', 'public');
        }

        $puesto->update($data);

        return redirect()->route('myjobs.index')->with('success', 'Puesto actualizado correctamente.');
    }

    // Eliminar un puesto
    public function destroy(Puesto $puesto)
    {

        if ($puesto->empresa_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar este puesto.');
        }

        if ($puesto->imagen && Storage::exists('public/' . $puesto->imagen)) {
            Storage::delete('public/' . $puesto->imagen);
        }

        $puesto->delete();

        return redirect()->route('myjobs.index')->with('success', 'Puesto eliminado correctamente.');
    }
}
