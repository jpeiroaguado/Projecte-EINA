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
            $table->foreignId('usuari_id')->constrained('usuaris')->onDelete('cascade');
            $table->foreignId('configuracio_ia_id')->nullable()->constrained('configuracio_ia')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('converses');
    }
};
