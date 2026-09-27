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
   Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable(); 
    $table->decimal('price', 8, 2);
    $table->double('rating')->default(0.0); 
    $table->string('image')->nullable();  
    $table->string('beans')->nullable();
    $table->string('serving');
    $table->string('cup_size');
    $table->string('origin')->nullable();
    
    $table->foreignId('category_id')
          ->constrained()
          ->cascadeOnDelete();

    $table->timestamps();
});
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
