<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    protected $casts = ['steps' => 'array'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // public function steps(): HasMany
    // {
    //     return $this->hasMany(Step::class);
    // }

    public function gameSessions(): HasMany
    {
        return $this->hasMany(GameSession::class);
    }
}
