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
    Schema::create('about_profiles', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('company_name', 180);
      $table->string('tagline', 255)->nullable();
      $table->longText('description')->nullable();
      $table->longText('vision')->nullable();
      $table->longText('mission')->nullable();
      $table->string('logo')->nullable();
      $table->string('hero_image')->nullable();
      $table->string('address', 255)->nullable();
      $table->string('email', 190)->nullable();
      $table->string('phone', 40)->nullable();
      $table->unsignedSmallInteger('founded_year')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('about_profiles');
  }
};
