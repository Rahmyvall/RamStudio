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
        Schema::table('portfolio_technology', function (Blueprint $table) {
            $table->foreign(['portfolio_id'], 'portfolio_technology_ibfk_1')->references(['id'])->on('portfolios')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['technology_id'], 'portfolio_technology_ibfk_2')->references(['id'])->on('technologies')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolio_technology', function (Blueprint $table) {
            $table->dropForeign('portfolio_technology_ibfk_1');
            $table->dropForeign('portfolio_technology_ibfk_2');
        });
    }
};
