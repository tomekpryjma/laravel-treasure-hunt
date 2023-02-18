<?php

namespace App\Http\Controllers;

use App\Helpers\UniqueUuid;
use App\Models\Game;
use App\Models\GameSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function register(Request $request)
    {
        $request->validate([
            'emails' => ['required', 'array', 'min:1', 'max:4'],
            'emails.*' => ['email'],
            'game_id' => ['required', 'exists:games,id']
        ]);

        $game = Game::find($request->input('game_id'));

        // TODO: once payment gateway is in, ensure no money gets taken if this errors.
        DB::transaction(function () use ($game) {
            // Create game session
            $game->gameSessions()->save(
                new GameSession([
                    'session_code' => UniqueUuid::generate((new GameSession)->getTable(), 'session_code'),
                ])
            );

            // Create players
            // TODO: Generate unique access_code
            // TODO: Think about how to prevent 1 access code being shared despite there being a limit to players.
            /**
             * Can use logic in UniqueUuid class for the access code.
             * 
             * If im planning on using websockets, maybe there's a way to check amount of connections. If there are more than total players, dont allow access?
             */

            // Create lobbies
        });
    }
}
