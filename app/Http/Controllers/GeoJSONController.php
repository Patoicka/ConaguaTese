<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeoJSON;

class GeoJSONController extends Controller
{
    /**
     * Obtiene el GeoJSON de un estado por su nombre.
     *
     * @param  string  $nombre
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGeoJSONByName($nombre)
    {
        // Buscar el GeoJSON por nombre en la base de datos
        $geojsonData = GeoJSON::where('nombre', $nombre)->first();

        // Si no se encuentra, devolver un error 404
        if (!$geojsonData) {
            return response()->json([
                "error" => "No se encontró un GeoJSON con el nombre proporcionado."
            ], 404);
        }

        // Formatear la respuesta en formato GeoJSON
        $geojson = [
            "type" => "FeatureCollection",
            "features" => [
                [
                    "type" => "Feature",
                    "properties" => [
                        "id" => $geojsonData->id,
                        "nombre" => $geojsonData->nombre
                    ],
                    "geometry" => json_decode($geojsonData->geom) // Convertir de string a JSON
                ]
            ]
        ];

        return response()->json($geojson);
    }
}
