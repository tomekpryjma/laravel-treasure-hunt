<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
// use Illuminate\Foundation\Auth\User as Authenticatable;

class Player extends Model implements AuthenticatableInterface
{
    use HasFactory, Authenticatable;

    protected $rememberTokenName = '';

    protected $fillable = ['name', 'email', 'game_session_id'];

    public function getAuthPassword()
    {
        return '';
    }

    public function lobby(): HasOne
    {
        return $this->hasOne(GameLobby::class);
    }

    public function gameSession(): BelongsTo
    {
        return $this->belongsTo(GameSession::class);
    }
}
