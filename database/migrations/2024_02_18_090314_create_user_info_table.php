<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_info', function (Blueprint $table) {
            $table->id();

            $table->string('device');
            $table->string('device_details');
            $table->string('brand');
            $table->foreignId('user_id')->constrained()->restrictOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_infos');
    }
};
