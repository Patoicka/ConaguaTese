<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormularioController;
use Illuminate\Support\Facades\Response;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/Formulario', function(){
    return view('Formulario');
});

Route::get('/estilo', function(){
    return view('style');
});

Route::post('/guardar', [FormularioController::class, 'guardar'])->name('guardarFormulario');



Route::get('/geojson/mexico', function () {
    $path = public_path('geojson/mexico.geojson');
    return Response::file($path, ['Content-Type' => 'application/json']);
});

