<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('reportes', function (Blueprint $table) {
            if (!Schema::hasColumn('reportes', 'latitud')) {
                $table->string('latitud');
            }
            if (!Schema::hasColumn('reportes', 'longitud')) {
                $table->string('longitud');
            }
            if (!Schema::hasColumn('reportes', 'opciones')) {
                $table->string('opciones');
            }
            if (!Schema::hasColumn('reportes', 'municipio')) {
                $table->string('municipio');
            }
        });
    }
    

public function down()
{
    Schema::table('reportes', function (Blueprint $table) {
        $table->dropColumn(['latitud', 'longitud', 'opciones', 'municipio']);
    });
}

};
