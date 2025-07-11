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
        Schema::create('contexts', function (Blueprint $table) {
            $table->id();
            $table->string('titol');
            $table->text('descripcio')->nullable();
            $table->string('descripcio_curta')->nullable();
            $table->integer('interaccions_max')->default(10);
            $table->boolean('actiu')->default(false)->index();
            $table->foreignId('creat_per')->constrained('usuaris')->onDelete('cascade')->index();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contexts');
    }
};
