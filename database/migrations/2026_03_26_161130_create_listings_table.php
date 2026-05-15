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
    Schema::create('listings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('category_id')->constrained();
        $table->string('title');
        $table->text('description');
        $table->decimal('price', 15, 2);
        $table->string('location');
        
        // AJOUTE CETTE LIGNE SI ELLE MANQUE :
        $table->string('type')->default('location'); // location ou vente
        
        $table->string('cover_image')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
