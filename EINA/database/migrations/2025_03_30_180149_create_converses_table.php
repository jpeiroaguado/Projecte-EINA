<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('converses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('usuari_id')->constrained('usuaris');
    $table->foreignId('context_id')->constrained('contexts')->onDelete('cascade');
    $table->string('gemini_history_id')->nullable();
    $table->unsignedInteger('interaccions_restants')->default(0);
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('converses');
    }
};
