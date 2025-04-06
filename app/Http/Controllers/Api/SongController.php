<?php

namespace App\Http\Controllers\Api;

use App\Models\Song;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SongController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10); // número de itens por página
        $offset = $request->input('offset', 0); // quantos pular, ex: 5 para começar na 6ª
    
        $songs = \App\Models\Song::query()
            ->offset($offset)
            ->limit($perPage)
            ->get();
    
        return response()->json([
            'data' => $songs,
            'offset' => $offset,
            'per_page' => $perPage,
            'count' => $songs->count(),
        ]);
    }

    public function topPlayed()
    {
        $topSongs = \App\Models\Song::orderByDesc('play_count')
            ->limit(5)
            ->get();
    
        return response()->json([
            'data' => $topSongs,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'youtube_link' => 'required|url'
        ]);

        $song = Song::create($request->only(['title', 'youtube_link']));
        return response()->json($song, 201);
    }

    public function update(Request $request, Song $song)
    {
        $song->update($request->only(['title', 'youtube_link']));
        return response()->json($song);
    }

    public function destroy(Song $song)
    {
        $song->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
