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
        Schema::create('clans', function (Blueprint $table) {
            $table->id();
            $table->string('tag')->unique();
            $table->string('name');
            $table->string('type');
            $table->text('description')->nullable();
            $table->string('location_name')->nullable();
            $table->boolean('is_family_friendly')->default(false);
            $table->json('badge_urls');
            $table->integer('clan_level');
            $table->integer('clan_points')->default(0);
            $table->integer('clan_builder_base_points')->default(0);
            $table->integer('clan_capital_points')->default(0);
            $table->string('capital_league_name')->nullable();
            $table->integer('required_trophies')->default(0);
            $table->string('war_frequency')->nullable();
            $table->integer('war_win_streak')->default(0);
            $table->integer('war_wins')->default(0);
            $table->integer('war_ties')->default(0);
            $table->integer('war_losses')->default(0);
            $table->boolean('is_war_log_public')->default(true);
            $table->string('war_league_name')->nullable();
            $table->integer('members')->default(0);
            $table->json('member_list')->nullable();
            $table->json('clan_capital')->nullable();
            $table->string('chat_language')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clans');
    }
};
