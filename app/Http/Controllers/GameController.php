<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::all();
        return response()->json($games);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'size' => 'required|integer',
            'tools' => 'required|json',
        ]);

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $path = $request->file->store('public/games');
            $path = Storage::url($path);

            $game = new Game;
            $game->path = $path;
            $game->size = $request->size;
            $game->tools = $request->tools;
            $game->save();

            return response()->json($game, 201);
        }

        return response()->json(['error' => 'Invalid file upload'], 400);
    }
}
