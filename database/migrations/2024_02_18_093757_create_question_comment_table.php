<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_comment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->text('comment');
            $table->enum('status',['seen','not_seen'])->default('not_seen');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_comment');
    }
};
