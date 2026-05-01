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
    Schema::create('greetings', function (Blueprint $table): void {
      $table->id();
      $table->string('name');
      $table->text('message');
      $table->string('bg_color'); // hex color code (e.g., #FF5733)
      $table->string('image_path'); // path to stored image
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('greetings');
  }
};
