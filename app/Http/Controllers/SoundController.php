<?php

namespace App\Http\Controllers;

use App\Models\Sound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class SoundController extends Controller
{
    // Display a listing of sounds. Return JSON.
    public function index()
    {
        // Check if sounds exist in the cache
        $sounds = Redis::get('sounds');

        // If sounds are found in the cache, return them
        if ($sounds) {
            return response()->json(json_decode($sounds));
        }

        // If sounds are not found in the cache, fetch them from the database
        $sounds = Sound::all();

        // Save sounds to the cache for future requests
        Redis::set('sounds', json_encode($sounds));

        return response()->json($sounds);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sound $sound)
    {
        // Return JSON response with the player
        return response()->json($sound);
    }

}
