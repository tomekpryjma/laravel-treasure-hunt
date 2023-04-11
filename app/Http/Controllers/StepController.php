<?php

namespace App\Http\Controllers;

use App\Http\Requests\StepStoreRequest;
use App\Models\Game;
use App\Models\Step;
use Illuminate\Http\Request;

class StepController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(StepStoreRequest $request, Game $game)
    {
        $step = new Step($request->validated());

        if ($game->steps()->save($step)) {
            return response()->json([
                'message' => 'Step saved!',
            ], 201);
        }
        return response()->json([
            'message' => 'Failed to save step.',
        ], 500);
    }

    public function update(StepStoreRequest $request, Step $step)
    {
        if ($step->update($request->validated())) {
            return response()->json(['message' => 'Step updated!']);
        }
        return response()->json(['message' => 'Failed to update step.']);
    }
}
