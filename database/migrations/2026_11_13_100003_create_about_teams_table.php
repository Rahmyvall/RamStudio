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
    Schema::create('about_teams', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('name', 150);
      $table->string('position', 150)->nullable();
      $table->string('photo')->nullable();
      $table->longText('bio')->nullable();
      $table->string('email', 190)->nullable();
      $table->string('linkedin_url')->nullable();
      $table->string('instagram_url')->nullable();
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
    Schema::dropIfExists('about_teams');
  }
};
