<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Configuration extends Controller
{
    public function index()
    {
        $path = storage_path('app/configuration.json');
        $settings = [];

        if (file_exists($path)) {
            $settings = json_decode(file_get_contents($path), true) ?: [];
        }

        return response()->json($settings);
    }

    public function show(string $key)
    {
        $path = storage_path('app/configuration.json');
        $settings = [];

        if (file_exists($path)) {
            $settings = json_decode(file_get_contents($path), true) ?: [];
        }

        if (!array_key_exists($key, $settings)) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        return response()->json([$key => $settings[$key]]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key'   => 'required|string',
            'value' => 'required'
        ]);

        $path = storage_path('app/configuration.json');
        $settings = [];

        if (file_exists($path)) {
            $settings = json_decode(file_get_contents($path), true) ?: [];
        }

        if (array_key_exists($data['key'], $settings)) {
            return response()->json(['message' => 'La clave ya existe'], 409);
        }

        $settings[$data['key']] = $data['value'];
        file_put_contents($path, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return response()->json(['message' => 'Creado', 'key' => $data['key']], 201);
    }

    public function update(Request $request, string $key)
    {
        $data = $request->validate([
            'value' => 'required'
        ]);

        $path = storage_path('app/configuration.json');
        $settings = [];

        if (file_exists($path)) {
            $settings = json_decode(file_get_contents($path), true) ?: [];
        }

        $settings[$key] = $data['value'];
        file_put_contents($path, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return response()->json(['message' => 'Actualizado', 'key' => $key, 'value' => $data['value']]);
    }

    public function destroy(string $key)
    {
        $path = storage_path('app/configuration.json');

        if (!file_exists($path)) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        $settings = json_decode(file_get_contents($path), true) ?: [];

        if (!array_key_exists($key, $settings)) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        unset($settings[$key]);
        file_put_contents($path, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return response()->json(['message' => 'Eliminado']);
    }
}
