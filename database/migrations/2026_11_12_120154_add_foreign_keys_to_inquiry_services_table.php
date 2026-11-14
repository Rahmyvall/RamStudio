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
        Schema::table('inquiry_services', function (Blueprint $table) {
            $table->foreign(['inquiry_id'], 'inquiry_services_ibfk_1')->references(['id'])->on('inquiries')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['service_id'], 'inquiry_services_ibfk_2')->references(['id'])->on('services')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiry_services', function (Blueprint $table) {
            $table->dropForeign('inquiry_services_ibfk_1');
            $table->dropForeign('inquiry_services_ibfk_2');
        });
    }
};
