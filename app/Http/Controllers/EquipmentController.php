<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipments = \App\Models\Equipment::orderBy('created_at', 'desc')->paginate(15);
        return view('equipment.index', compact('equipments'));
    }

    public function create()
    {
        return view('equipment.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'serial' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|max:50',
        ]);

        \App\Models\Equipment::create($data);

        return redirect()->route('equipment.index')->with('success', 'Equipo creado correctamente.');
    }

    public function show(\App\Models\Equipment $equipment)
    {
        return view('equipment.show', compact('equipment'));
    }

    public function edit(\App\Models\Equipment $equipment)
    {
        return view('equipment.edit', compact('equipment'));
    }

    public function update(Request $request, \App\Models\Equipment $equipment)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'serial' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|max:50',
        ]);

        $equipment->update($data);

        return redirect()->route('equipment.index')->with('success', 'Equipo actualizado correctamente.');
    }

    public function destroy(\App\Models\Equipment $equipment)
    {
        $equipment->delete();
        return redirect()->route('equipment.index')->with('success', 'Equipo eliminado correctamente.');
    }
}
