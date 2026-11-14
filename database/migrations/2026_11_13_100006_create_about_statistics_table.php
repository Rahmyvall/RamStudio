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
    Schema::create('about_statistics', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('label', 150);
      $table->string('value', 50);
      $table->string('suffix', 20)->nullable();
      $table->string('icon', 100)->nullable();
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
    Schema::dropIfExists('about_statistics');
  }
};
