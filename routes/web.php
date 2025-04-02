<?php

use App\Http\Controllers\GeoJSONController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormularioController;
use Illuminate\Support\Facades\Response;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/Formulario', function () {
    return view('Formulario');
});

Route::get('/estilo', function () {
    return view('style');
});

Route::post('/guardar', [FormularioController::class, 'guardar'])->name('guardarFormulario');

Route::get('/geojson/{nombre}', [GeoJSONController::class, 'getGeoJSONByName']);
