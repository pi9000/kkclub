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
        Schema::create('website_lists', function (Blueprint $table) {
            $table->id();
            $table->string('agent_id');
            $table->string('domain');
            $table->string('apikey');
            $table->string('secretkey');
            $table->string('template');
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_lists');
    }
};
