<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $game = new Game(['title' => $request->input('title')]);

        if ($request->user()->games()->save($game)) {
            return response()->json(['message' => 'Game created!'], 201);
        }
    }
}
