<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Services\YouTubeService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class YouTubeController
{
    protected $youtubeService;

    public function __construct(YouTubeService $youtubeService)
    {
        $this->youtubeService = $youtubeService;
    }

    public function search(Request $request)
    {
        $query = $request->input('query', '');
        $videos = $this->youtubeService->searchVideos($query);

        return Inertia::render('YouTube/Search', [
            'videos' => $videos,
            'query' => $query,
        ]);
    }

    public function random()
    {
        try {
            $randomVideo = $this->youtubeService->getRandomVideo();
    
            if (is_null($randomVideo)) {
                return Inertia::render('YouTube/Random', [
                    'error' => 'Non sono stati trovati video per la tua richiesta. Riprova.',
                ]);
            }
    
            return Inertia::render('YouTube/Random', [
                'video' => $randomVideo,
            ]);
        } catch (\Exception $e) {
            return Inertia::render('YouTube/Random', [
                'error' => 'Quota API superata. Riprova più tardi.',
            ]);
        }
    }
    
    

    public function favorites()
    {
        $favorites = Favorite::where('user_id', Auth::id())->get();

        return Inertia::render('YouTube/Favorites', [
            'favorites' => $favorites,
        ]);
    }
}
