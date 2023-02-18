<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Player extends Model
{
    use HasFactory;

    public function lobby(): HasOne
    {
        return $this->hasOne(GameLobby::class);
    }

    public function gameSession(): HasOneThrough
    {
        return $this->hasOneThrough(GameSession::class, GameLobby::class);
    }
}
