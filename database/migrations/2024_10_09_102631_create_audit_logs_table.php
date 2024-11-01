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
        Schema::create('audit_logs', function (Blueprint $table) {

                    $table->id();
                    $table->unsignedBigInteger('user_id')->nullable(); // L'ID de l'utilisateur
                    $table->string('action'); // Le type d'action (ex : "validation", "modification")
                    $table->string('table_name'); // Le nom de la table impactée (ex : "opportunities", "offers")
                    $table->json('old_values')->nullable(); // Les anciennes valeurs avant modification
                    $table->json('new_values')->nullable(); // Les nouvelles valeurs après modification
                    $table->string('ip_address')->nullable(); // L'adresse IP de l'utilisateur
                    $table->timestamps(); // Date et heure de l'action


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
