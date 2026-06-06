<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brankas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->bigInteger('target_price');
            $table->enum('priority', ['tinggi', 'sedang', 'rendah'])->default('sedang');
            $table->text('description')->nullable();
            $table->enum('status', ['belum_tercapai', 'tercapai'])->default('belum_tercapai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brankas');
    }
};



