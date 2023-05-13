<?php

namespace App\Services;

use App\Models\GameSession;

class GameSessionAuthService
{
    public function verifySessionAccessCode(GameSession $gameSession)
    {
        return session()->has('session_access_code')
            && session()->get('session_access_code') == $gameSession->access_code;
    }

    public function storeSessionAccessCode($accessCode)
    {
        session()->put('session_access_code', $accessCode);
    }

    public function removeAuth()
    {
        session()->remove('session_access_code');
    }
}
