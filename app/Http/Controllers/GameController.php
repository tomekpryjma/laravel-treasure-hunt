<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        if (Game::create(['title' => $request->input('title')])) {
            return response()->json(['message' => 'Game created!'], 201);
        }
    }
}
