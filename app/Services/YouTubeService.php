<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class YouTubeService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.youtube.key');
    }

    public function searchVideos($query)
    {
        if (empty($query)) {
            return [];
        }

        $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
            'part' => 'snippet',
            'q' => $query,
            'type' => 'video',
            'maxResults' => 10,
            'videoCategoryId' => '10', // Category ID for Music
            'key' => $this->apiKey,
        ]);

        return $response->successful() ? $this->formatVideos($response->json()['items']) : [];
    }

    public function getRandomVideo()
    {
        if (cache()->has('youtube_videos')) {
            $videos = cache('youtube_videos');
        } else {
            $queries = ['sample audio', 'chill beats', 'jazz samples', 'guitar solo', 'drum loop'];
            $randomQuery = $queries[array_rand($queries)];
    
            $videos = $this->searchVideos($randomQuery);
    
            // Memorizza in cache per 1 ora
            cache()->put('youtube_videos', $videos, now()->addHour());
        }
    
        if (!empty($videos)) {
            $randomVideo = $videos[array_rand($videos)];
            return $randomVideo;
        }
    
        return null;
    }
    

    protected function formatVideos($videos)
    {
        return array_map(function ($video) {
            return [
                'video_id' => $video['id']['videoId'],
                'title' => $video['snippet']['title'],
                'thumbnail' => $video['snippet']['thumbnails']['medium']['url'],
            ];
        }, $videos);
    }
}
