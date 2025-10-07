<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Cargar ventas con usuario y productos relacionados
        $sales = Sale::with(['user', 'products'])->get();

        return view('dashboard', [
          //  'heading' => 'Resumen de Ventas',
            'sales' => $sales
        ]);
    }
}

