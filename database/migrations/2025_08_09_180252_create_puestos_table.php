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
        Schema::create('puestos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('users')->onDelete('cascade'); 
            // empresa_id hace referencia al id de users (tipo = 'e')
            $table->string('nombre');
            $table->text('descripcion');
            $table->string('imagen')->nullable();
            $table->timestamp('fecha_publicacion')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puestos');
    }
};
