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
        Schema::table('opportunities', function (Blueprint $table) {
            $table->unsignedBigInteger('offer_id')->nullable()->after('date_validation');
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('set null');
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opportunities', function (Blueprint $table) {
            //
            $table->dropForeign(['offer_id']);
            $table->dropColumn('offer_id');
        });
    }
};
