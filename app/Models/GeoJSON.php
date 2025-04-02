<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeoJSON extends Model
{
    use HasFactory;

    protected $table = 'geojson';

    protected $fillable = ['id', 'nombre', 'geom'];

}

