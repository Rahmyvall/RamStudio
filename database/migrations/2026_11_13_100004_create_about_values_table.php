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
    Schema::create('about_values', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('title', 180);
      $table->text('description')->nullable();
      $table->string('icon', 100)->nullable();
      $table->string('type', 30)->nullable()->default('value');
      $table->integer('sort_order')->nullable()->default(0);
      $table->boolean('is_active')->nullable()->default(true);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('about_values');
  }
};
