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
        Schema::create('investors', function (Blueprint $table) {
            $table->id();
            $table->enum('civilite', ['M', 'Mme', 'Dr', 'Prof'])->nullable();
            $table->string('prenom')->nullable();
            $table->string('nom')->nullable();
            $table->string('pays')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('fonction')->nullable();
            $table->enum('langue_preferee', ['Français', 'Anglais', 'Arabe'])->default('Français');
            $table->text('remarques')->nullable();
            $table->timestamp('derniere_interaction')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->string('mobile')->nullable();
            $table->enum('niveau_influence', ['Faible', 'Moyen', 'Élevé', 'Critique'])->default('Moyen');
            $table->json('tags')->nullable();
            $table->unsignedBigInteger('categorie_id')->nullable();
            $table->foreign('categorie_id')->references('id')->on('categories_investisseurs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investors');
    }
};
