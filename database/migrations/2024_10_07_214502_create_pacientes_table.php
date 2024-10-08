<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->unsignedBigInteger('hospital_id');
            $table->unsignedBigInteger('plano_saude_id');
            $table->timestamps();

            // Chaves estrangeiras.
            $table->foreign('hospital_id')->references('id')->on('hospitais');
            $table->foreign('plano_saude_id')->references('id')->on('planos_saude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropForeign(['hospital_id']);
            $table->dropForeign(['plano_saude_id']);
        });

        Schema::dropIfExists('pacientes');
    }
};
