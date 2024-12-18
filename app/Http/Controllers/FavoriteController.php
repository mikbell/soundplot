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
            'track_id' => 'required|string',
            'title' => 'required|string',
            'artist_name' => 'required|string',
            'audio_url' => 'required|url',
            'license_url' => 'nullable|url',
            'album_name' => 'required|string',
            'album_image' => 'required|url',
            'release_date' => 'required|date',
            'audiodownload' => 'nullable|url',
        ]);

        Favorite::create([
            'user_id' => Auth::id(),
            'track_id' => $request->input('track_id'),
            'title' => $request->input('title'),
            'artist_name' => $request->input('artist_name'),
            'audio_url' => $request->input('audio_url'),
            'license_url' => $request->input('license_url'),
            'album_name' => $request->input('album_name'),
            'album_image' => $request->input('album_image'),
            'release_date' => $request->input('release_date'),
            'audiodownload' => $request->input('audiodownload'),
        ]);

        return back()->with('success', 'Traccia aggiunta ai preferiti!');
    }

    public function destroy($trackId)
    {
        $favorite = Favorite::where('user_id', Auth::id())->where('track_id', $trackId)->first();

        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'Traccia rimossa dai preferiti!');
        }

        return back()->with('error', 'Traccia non trovata!');
    }
}
