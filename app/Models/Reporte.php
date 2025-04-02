<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    use HasFactory;

    protected $table = 'reportes'; // Verifica que el nombre coincide con la tabla

    protected $fillable = ['latitud', 'longitud', 'opciones', 'municipio']; 

    public $timestamps = false;

 
}

