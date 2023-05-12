<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_lobbies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_session_id')->constrained();
            $table->foreignId('player_id')->constrained();
            $table->uuid('lobby_code')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_lobbies');
    }
};
