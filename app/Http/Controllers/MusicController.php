<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\MusicService;

class MusicController
{
    protected $musicService;

    public function __construct(MusicService $musicService)
    {
        $this->musicService = $musicService;
    }

    /**
     * Mostra le tracce popolari nella dashboard.
     */
    public function index()
    {
        $tracks = $this->musicService->getPopularTracks(21);

        return Inertia::render('Dashboard', [
            'initialTracks' => $tracks,
        ]);
    }

    /**
     * Ricerca le tracce musicali tramite parametro "query".
     */
    public function search(Request $request)
    {
        $query = $request->input('query', null);

        if (!$query) {
            return response()->json(['error' => 'Parametro "query" mancante'], 422);
        }

        $tracks = $this->musicService->searchTracks($query);

        return response()->json($tracks);
    }


    /**
     * Mostra una traccia casuale, con filtro per genere opzionale.
     */
    public function random(Request $request)
    {
        $validated = $request->validate([
            'genre' => 'nullable|string|max:50',
        ]);

        try {
            $genre = $validated['genre'] ?? null;
            $randomTrack = $this->musicService->getRandomTracks(1, $genre);

            if (empty($randomTrack)) {
                return Inertia::render('Music/Random', [
                    'error' => 'Non sono stati trovati brani per la tua richiesta. Riprova.',
                    'selectedGenre' => $genre,
                ]);
            }

            return Inertia::render('Music/Random', [
                'track' => $randomTrack[0],
                'selectedGenre' => $genre,
            ]);
        } catch (\Exception $e) {
            return Inertia::render('Music/Random', [
                'error' => 'Quota API superata o errore di connessione. Riprova più tardi.',
                'selectedGenre' => $request->input('genre'),
            ]);
        }
    }
}
