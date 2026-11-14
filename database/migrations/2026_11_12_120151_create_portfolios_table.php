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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('portfolio_category_id')->nullable()->index('portfolio_category_id');
            $table->string('title', 180);
            $table->string('slug', 200)->unique('slug');
            $table->string('client_name', 180)->nullable();
            $table->string('project_url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->longText('description')->nullable();
            $table->date('project_date')->nullable();
            $table->boolean('is_featured')->nullable()->default(false);
            $table->boolean('is_published')->nullable()->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
