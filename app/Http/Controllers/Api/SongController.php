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
    
        $title = $request->input('title'); 
        $sortBy = $request->input('sort_by', 'id'); 
        $sortDir = $request->input('sort_dir', 'asc'); 
    
        $query = Song::query();
    
        if ($title) {
            $query->where('title', 'like', "%{$title}%");
        }
    
        $sortable = ['id', 'title', 'created_at', 'updated_at', 'plays'];
    
        if (in_array($sortBy, $sortable)) {
            $query->orderBy($sortBy, $sortDir === 'desc' ? 'desc' : 'asc');
        }
    
        $total = $query->count(); 
        $songs = $query->skip($offset)->take($perPage)->get();
    
        return response()->json([
            'data' => $songs,
            'total' => $total,
            'per_page' => $perPage,
            'page' => $page, 
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
