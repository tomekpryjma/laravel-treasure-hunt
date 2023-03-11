<?php

namespace App\Http\Controllers;

use App\Helpers\Unique;
use App\Models\Game;
use App\Models\GameSession;
use App\Models\Player;
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
        $gameSessionCreated = false;

        // TODO: once payment gateway is in, ensure no money gets taken if this errors.
        DB::transaction(function () use ($game, $request, &$gameSessionCreated) {
            $tableName = (new GameSession)->getTable();
            $sessionCode = Unique::uuid($tableName, 'session_code');
            $game->gameSessions()->save(
                new GameSession([
                    'session_code' => $sessionCode,
                    'access_code' => Unique::number($tableName, 'access_code'),
                ])
            );

            /**
             * Registering user will input emails of the other players.
             * The email will be used with the game session access code to authenticate players
             * into the game session.
             * An email should be sent to all entered emails.
             */
            $gameSession = GameSession::where('session_code', $sessionCode)->first();
            $players = [];

            foreach ($request->input('emails') as $email) {
                $players[] = new Player([
                    'email' => $email,
                ]);
            }

            $gameSession->players()->saveMany($players);
            $gameSessionCreated = true;
        });

        if ($gameSessionCreated) {
            return response()->json([
                'message' => 'Thank you for registering for this game!',
            ], 201);
        }
    }
}
