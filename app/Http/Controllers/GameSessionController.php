<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GameSessionController extends Controller
{
    public function lobby(Request $request, $sessionCode)
    {
        $gameSession = GameSession::byCode($sessionCode)->first();

        if (!$gameSession) {
            abort(404);
        }

        return view('game-session.lobby', [
            'gameSession' => $gameSession,
        ]);
    }

    public function show(Request $request, $sessionCode)
    {
        $validator = Validator::make($request->all(), [
            'access_code' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $gameSession = GameSession::byCode($sessionCode)
            ->where('access_code', $request->input('access_code'))
            ->with('game')
            ->first();

        if (!$gameSession) {
            abort(404);
        }

        return view('game-session.show', [
            'gameSession' => $gameSession,
            'accessCode' => $request->input('access_code'),
        ]);
    }
}
