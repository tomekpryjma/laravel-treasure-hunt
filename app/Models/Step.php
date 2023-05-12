<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Step extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'messages', 'accepted_answers'];

    protected $casts = [
        'messages' => 'array',
        'accepted_answers' => 'array'
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
