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
        Schema::create('interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['Email', 'Appel', 'Réunion', 'Email envoyé', 'Email reçu', 'Autre']);
            $table->date('date_interaction');
            $table->text('description');
            $table->string('piece_jointe')->nullable();
            $table->json('metadata')->nullable(); // Pour stocker des infos supplémentaires
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index(['investor_id', 'date_interaction']);
            $table->index('type');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interactions');
    }
};
