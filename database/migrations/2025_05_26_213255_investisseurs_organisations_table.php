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
        Schema::create('investisseur_organisations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_id')->constrained()->onDelete('cascade');
            $table->foreignId('organisation_id')->constrained()->onDelete('cascade');
            $table->string('poste')->nullable();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->boolean('actuel')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['investor_id', 'organisation_id', 'poste'], 'investor_org_poste_unique');
            $table->index(['investor_id', 'actuel']);
            $table->index(['organisation_id', 'actuel']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investisseur_organisations');
    }
};
