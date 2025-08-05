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
        Schema::create('taruhans', function (Blueprint $table) {
            $table->id();
            $table->string('tid');
            $table->string('market');
            $table->string('amount');
            $table->string('pasang');
            $table->string('date');
            $table->string('win');
            $table->string('type');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taruhans');
    }
};
