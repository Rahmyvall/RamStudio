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
        Schema::create('inquiry_services', function (Blueprint $table) {
            $table->unsignedBigInteger('inquiry_id');
            $table->unsignedBigInteger('service_id')->index('service_id');

            $table->primary(['inquiry_id', 'service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiry_services');
    }
};
