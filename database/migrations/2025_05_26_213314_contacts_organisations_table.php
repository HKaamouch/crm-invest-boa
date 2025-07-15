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
        Schema::create('contact_organisations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            $table->foreignId('organisation_id')->constrained()->onDelete('cascade');
            $table->string('poste')->nullable();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->boolean('actuel')->default(true);
            $table->timestamps();

            $table->unique(['contact_id', 'organisation_id', 'poste'], 'contact_org_poste_unique');
            $table->index(['contact_id', 'actuel']);
            $table->index(['organisation_id', 'actuel']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_organisations');
    }
};
