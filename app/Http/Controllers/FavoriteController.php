<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController
{
    public function store(Request $request)
    {
        $request->validate([
            'video_id' => 'required|string',
        ]);
    
        Favorite::create([
            'user_id' => Auth::id(),
            'video_id' => $request->input('video_id'),
        ]);
    
        return response()->json(['message' => 'Traccia aggiunta ai preferiti!'], 200);
    }
    

    public function destroy($videoId)
    {
        $favorite = Favorite::where('user_id', Auth::id())->where('video_id', $videoId)->first();
    
        if ($favorite) {
            $favorite->delete();
            return response()->json(['message' => 'Traccia rimossa dai preferiti!'], 200);
        }
    
        return response()->json(['error' => 'Traccia non trovata!'], 404);
    }
    
}
