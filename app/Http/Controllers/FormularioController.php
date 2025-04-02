<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reporte;
use App\Models\GeoJSON;


class FormularioController extends Controller
{
    public function guardar(Request $request)
    {
        // Verifica si se están recibiendo los datos
        //dd($request->all()); // Esto imprimirá los datos enviados

        // Guarda en la base de datos
        Reporte::create([
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'opciones' => $request->opciones,
            'municipio' => $request->municipio
        ]);

        return redirect()->back()->with('success', 'Reporte enviado con éxito.');
    }
}

