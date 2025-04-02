<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id(); // Columna ID autoincremental
            $table->string('latitud'); // Columna para latitud
            $table->string('longitud'); // Columna para longitud
            $table->string('opciones'); // Columna para la opción seleccionada
            $table->string('municipio'); // Columna para el municipio
            $table->timestamps(); // Crea 'created_at' y 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('reportes');
    }
};
