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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nom_complet')->after('name');
            $table->string('telephone')->nullable()->after('email');
            $table->enum('statut', ['actif', 'inactif'])->default('actif')->after('telephone');
            $table->timestamp('derniere_connexion')->nullable()->after('statut');
            $table->string('adresse_ip')->nullable()->after('derniere_connexion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nom_complet', 'telephone', 'statut', 'derniere_connexion', 'adresse_ip']);

        });
    }
};
