<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use App\Services\GameSessionAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GameSessionController extends Controller
{
    private $gameSessionAuthService;

    public function __construct(GameSessionAuthService $gameSessionAuthService)
    {
        $this->gameSessionAuthService = $gameSessionAuthService;
    }
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

    public function attemptAccess(Request $request, $sessionCode)
    {
        $validator = Validator::make($request->all(), [
            'access_code' => ['required', 'numeric', 'max_digits:20'],
        ]);

        if ($validator->fails()) {
            $this->gameSessionAuthService->removeAuth();
            return back()->withErrors($validator->errors());
        }

        $gameSession = GameSession::byCode($sessionCode)
            ->where('access_code', $request->input('access_code'))
            ->first();

        if (!$gameSession) {
            abort(404);
        }

        $this->gameSessionAuthService->storeSessionAccessCode($gameSession->access_code);

        return redirect()->route('game-session.show', [
            'sessionCode' => $sessionCode,
        ]);
    }

    public function show(Request $request, $sessionCode)
    {
        $gameSession = GameSession::byCode($sessionCode)
            ->with('game')
            ->first();

        if (!$this->gameSessionAuthService->verifySessionAccessCode($gameSession)) {
            abort(404);
        }

        return view('game-session.show', [
            'gameSession' => $gameSession,
            'accessCode' => $request->input('access_code'),
        ]);
    }
}
