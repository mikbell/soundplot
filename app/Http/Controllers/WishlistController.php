<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController
{
    public function index()
    {
        $user = Auth::user();

        // Recupera tutti i campioni salvati nella wishlist dell'utente
        $wishlistSamples = $user->wishlistSamples()->with('wishlists')->get();

        return inertia('Wishlist/Index', [
            'samples' => $wishlistSamples,
        ]);
    }

    public function store(Request $request, Sample $sample)
    {
        $user = Auth::user();

        // Verifica se il campione è già nella wishlist
        if (!$user->wishlistSamples->contains($sample->id)) {
            $user->wishlists()->create([
                'sample_id' => $sample->id,
            ]);
        }

        return redirect()->back()->with('success', 'Campione aggiunto alla wishlist!');
    }

    public function destroy(Sample $sample)
    {
        $user = Auth::user();

        // Trova l'elemento wishlist associato all'utente e al campione
        $wishlist = $user->wishlists()->where('sample_id', $sample->id)->first();

        if ($wishlist) {
            $wishlist->delete();
        }

        return redirect()->back()->with('success', 'Campione aggiunto alla wishlist!');
    }
}

