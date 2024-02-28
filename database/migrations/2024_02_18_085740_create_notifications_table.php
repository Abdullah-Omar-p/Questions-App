<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('message',500);
            $table->string('type')->nullable();
            $table->integer('related_id')->nullable();

            // this for user who causes this notification
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            // $table->string('related_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
