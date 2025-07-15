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
        Schema::create('categories_investisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('couleur_hexa', 7)->default('#3B82F6'); // Bleu par défaut
            $table->unsignedInteger('ordre_affichage')->default(0);
            $table->boolean('actif')->default(true);
            $table->timestamps();

            $table->index('actif');
            $table->index('ordre_affichage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories_investisseurs');
    }
};
