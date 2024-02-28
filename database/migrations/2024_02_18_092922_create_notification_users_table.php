<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained()->restrictOnDelete();

            // this for user who will deliver the notification
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->enum('status',['delivered','seen'])->default('delivered'); // .. Delivered Or Seen ..
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_users');
    }
};
