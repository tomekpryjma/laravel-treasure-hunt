<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:player');
    }

    public function login(Request $request)
    {
        Auth::guard('player')->user();
        // $credentials = $request->validate([
        //     'email' => ['required', 'email', 'exists:players'],
        //     'game_session_id' => ['required', 'integer', 'exists:game_sessions'],
        // ]);

        // if (Auth::guard('player')->attempt($credentials)) {
        //     $request->session()->regenerate();
        //     return redirect()->route('game-session.test');
        // }
    }

    public function test()
    {
        return "hello world";
    }
}
