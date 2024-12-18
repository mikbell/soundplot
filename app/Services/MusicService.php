<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MusicService
{
    protected $clientId;

    public function __construct()
    {
        $this->clientId = config('services.jamendo.client_id');
    }

    protected function getCachedData($cacheKey, $callback, $cacheDuration = 60)
    {
        if (cache()->has($cacheKey)) {
            return cache($cacheKey);
        }

        $data = $callback();

        if (!empty($data)) {
            cache()->put($cacheKey, $data, now()->addMinutes($cacheDuration));
        }

        return $data;
    }

    public function searchTracks($query = null, $genre = null, $offset = 0, $limit = 21)
    {
        // Genera una chiave cache univoca basata sui parametri di ricerca
        $cacheKey = 'jamendo_search_tracks_' . md5(json_encode([
            'query' => $query,
            'genre' => $genre,
            'offset' => $offset,
            'limit' => $limit,
        ]));

        // Usa la funzione di caching
        return $this->getCachedData($cacheKey, function () use ($query, $genre, $offset, $limit) {
            $params = [
                'client_id' => $this->clientId,
                'format' => 'json',
                'limit' => $limit,
                'offset' => $offset,
                'order' => 'relevance',
                'include' => 'licenses',
            ];

            if ($query) {
                $params['search'] = $query;
            }

            if ($genre) {
                $params['tags'] = $genre;
            }

            $response = Http::get('https://api.jamendo.com/v3.0/tracks', $params);

            if ($response->successful()) {
                return $this->formatTracks($response->json()['results']);
            }

            \Log::error("Errore nella richiesta all'API di Jamendo: " . $response->body());
            return [];
        });
    }


    public function getPopularTracks($limit = 10, $offset = 0)
    {
        $cacheKey = "jamendo_popular_tracks_{$offset}_{$limit}";

        return $this->getCachedData($cacheKey, function () use ($limit, $offset) {
            $params = [
                'client_id' => $this->clientId,
                'format' => 'json',
                'limit' => $limit,
                'offset' => $offset,
                'order' => 'popularity_total', // Ordina per popolarità
                'include' => 'licenses',
            ];

            $response = Http::get('https://api.jamendo.com/v3.0/tracks', $params);

            if ($response->successful()) {
                return $this->formatTracks($response->json()['results']);
            }

            \Log::error("Errore nella richiesta dei brani popolari: " . $response->body());
            return [];
        });
    }


    public function getRandomTracks($limit = 30, $genre = null)
    {
        $offset = rand(0, 100); // Cambia l'offset per ottenere risultati diversi ogni volta
        $cacheKey = 'jamendo_random_tracks_' . $offset . ($genre ? '_' . md5($genre) : '');

        return $this->getCachedData($cacheKey, function () use ($limit, $offset, $genre) {
            return $this->searchTracks(null, $genre, $offset, $limit);
        });
    }


    protected function formatTracks($tracks)
    {
        return array_map(function ($track) {
            return [
                'track_id' => $track['id'] ?? null,
                'title' => $track['name'] ?? 'Titolo non disponibile',
                'artist_name' => $track['artist_name'] ?? 'Artista sconosciuto',
                'audio_url' => $track['audio'] ?? $track['audiodownload'] ?? null,
                'license_url' => $track['license_ccurl'] ?? null,
                'album_name' => $track['album_name'] ?? null,
                'album_image' => $track['album_image'] ?? asset('storage/images/default.jpg'),
                'release_date' => $track['releasedate'] ?? null,
                'lyrics' => $track['lyrics'] ?? null,
                'genre' => $track['genre'] ?? null,
                'audiodownload' => $track['audiodownload'] ?? null,
            ];
        }, $tracks);
    }
}
