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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tag')->unique();
            $table->integer('town_hall_level');
            $table->integer('xp');
            $table->integer('trophies')->default(0);
            $table->integer('best_trophies')->default(0);
            $table->integer('war_stars')->default(0);
            $table->string('clan_tag')->nullable();
            $table->string('clan_name')->nullable();
            $table->string('role')->nullable();
            $table->json('heroes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
