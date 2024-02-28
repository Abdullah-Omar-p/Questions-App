<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('question');
            $table->text('answer')->nullable();
            $table->enum('status',['opened','closed'])->default('opened');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('answered_by')->nullable();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
            $table->foreign('answered_by')->references('id')->on('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
