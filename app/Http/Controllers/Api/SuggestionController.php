<?php 

namespace App\Http\Controllers\Api;

use App\Models\Suggestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SuggestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Suggestion::with('user');
    
        if ($request->filled('status')) {
            $query->where('status', strtolower($request->input('status')));
        }
    
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
    
        $sortable = ['created_at', 'updated_at', 'status'];
    
        if (in_array($sortBy, $sortable)) {
            $query->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');
        }
    
        $perPage = $request->input('per_page', 10);
    
        return $query->paginate($perPage)->appends($request->query());
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'youtube_link' => 'required|url'
        ]);

        $suggestion = Suggestion::create([
            'user_id' => auth()->id(),
            'youtube_link' => $request->youtube_link,
        ]);

        return response()->json($suggestion, 201);
    }

    public function approve(Suggestion $suggestion)
    {
        $suggestion->update(['status' => 'approved']);
        return response()->json(['message' => 'Approved']);
    }

    public function reject(Suggestion $suggestion)
    {
        $suggestion->update(['status' => 'rejected']);
        return response()->json(['message' => 'Rejected']);
    }

    public function update(Request $request, Suggestion $suggestion)
    {
        $request->validate([
            'youtube_link' => 'required|url'
        ]);
    
        $suggestion->update([
            'youtube_link' => $request->youtube_link,
        ]);
    
        return response()->json(['message' => 'Updated']);
    }
    
    public function destroy(Suggestion $suggestion)
    {
        $suggestion->delete();
    
        return response()->json(['message' => 'Deleted']);
    }
}
