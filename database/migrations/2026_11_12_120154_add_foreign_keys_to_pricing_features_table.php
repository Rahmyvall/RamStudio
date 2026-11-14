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
        Schema::table('pricing_features', function (Blueprint $table) {
            $table->foreign(['pricing_package_id'], 'pricing_features_ibfk_1')->references(['id'])->on('pricing_packages')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pricing_features', function (Blueprint $table) {
            $table->dropForeign('pricing_features_ibfk_1');
        });
    }
};
