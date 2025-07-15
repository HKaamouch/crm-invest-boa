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
        Schema::create('investor_email_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_id')->constrained()->onDelete('cascade');
            $table->string('unique_email')->unique(); // ex: investor-1234@crm.ir-boa.com
            $table->string('identifier')->unique(); // ex: 1234
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('unique_email');
            $table->index('identifier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investor_email_addresses');
    }
};
