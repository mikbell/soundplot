<?php
namespace App\Http\Controllers;

use App\Models\Sample;
use Illuminate\Http\Request;

class SampleController
{
    // Metodo per visualizzare tutti i campioni
    public function index()
    {
        $samples = Sample::all();
        return inertia('Samples/Index', ['samples' => $samples]);
    }

    // Metodo per mostrare il form di creazione
    public function create()
    {
        return inertia('Samples/Create');
    }

    // Metodo per salvare un nuovo campione nel database
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $sample = new Sample();
        $sample->title = $request->input('title');
        $sample->description = $request->input('description');

        if ($request->hasFile('image')) {
            $sample->image = $request->file('image')->store('images', 'public');
        }

        $sample->save();

        return redirect()->route('samples.index')->with('success', 'Campione creato con successo!');
    }

    // Metodo per visualizzare i dettagli di un campione specifico
    public function show(Sample $sample)
    {
        return inertia('Samples/Show', ['sample' => $sample]);
    }

    // Metodo per mostrare il form di modifica
    public function edit(Sample $sample)
    {
        return inertia('Samples/Edit', ['sample' => $sample]);
    }

    // Metodo per aggiornare un campione esistente nel database
    public function update(Request $request, Sample $sample)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $sample->title = $request->input('title');
        $sample->description = $request->input('description');

        if ($request->hasFile('image')) {
            $sample->image = $request->file('image')->store('images', 'public');
        }

        $sample->save();

        return redirect()->route('samples.index')->with('success', 'Campione aggiornato con successo!');
    }

    // Metodo per cancellare un campione dal database
    public function destroy(Sample $sample)
    {
        $sample->delete();
        return redirect()->route('samples.index')->with('success', 'Campione eliminato con successo!');
    }
}
