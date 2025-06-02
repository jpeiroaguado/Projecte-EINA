<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('missatges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversa_id')->constrained('converses')->onDelete('cascade');
            $table->enum('remitent', ['alumne', 'ia']);
            $table->text('cos');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('missatges');
    }
};
