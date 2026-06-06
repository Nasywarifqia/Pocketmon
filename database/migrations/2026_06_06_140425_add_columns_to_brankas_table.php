<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brankas', function (Blueprint $table) {
            $table->bigInteger('collected_amount')->default(0)->after('target_price');
            $table->date('deadline')->nullable()->after('collected_amount');
        });
    }

    public function down(): void
    {
        Schema::table('brankas', function (Blueprint $table) {
            $table->dropColumn(['collected_amount', 'deadline']);
        });
    }
};