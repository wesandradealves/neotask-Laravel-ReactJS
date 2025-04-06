<?php

namespace App\Http\Controllers\Api;

use App\Models\Song;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SongController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $page = (int) $request->input('page', 1);
        $offset = ($page - 1) * $perPage;
    
        $songs = Song::query()
            ->offset($offset)
            ->limit($perPage)
            ->get();
    
        $total = Song::count();
    
        return response()->json([
            'data' => $songs,
            'page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'count' => $songs->count(),
        ]);
    }

    public function topPlayed()
    {
        $topSongs = \App\Models\Song::orderByDesc('plays')
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
