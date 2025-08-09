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
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('users')->onDelete('cascade'); 
            // cliente_id hace referencia a users (tipo = 'c')
            $table->foreignId('puesto_id')->constrained('puestos')->onDelete('cascade');
            $table->timestamp('fecha_publicacion')->useCurrent();
            $table->enum('estado', ['p', 'a', 'r'])->default('p'); // 'p' = pendiente, 'a' = aceptado, 'r' = rechazado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
