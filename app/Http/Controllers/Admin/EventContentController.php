<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventContentController extends Controller
{
    public function index()
    {
        return view('admin.event-content.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'events_hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);
        
        // Textes Hero
        Setting::updateOrCreate(['cle' => 'events_hero_title'], ['valeur' => $request->events_hero_title, 'description' => 'Titre principal du hero événements']);
        Setting::updateOrCreate(['cle' => 'events_hero_subtitle'], ['valeur' => $request->events_hero_subtitle, 'description' => 'Sous-titre du hero événements']);
        
        // Image Hero
        if ($request->hasFile('events_hero_image')) {
            $file = $request->file('events_hero_image');
            
            // Supprimer l'ancienne image si elle existe
            $oldImage = Setting::where('cle', 'events_hero_image')->first();
            if ($oldImage && $oldImage->valeur && Storage::disk('public')->exists($oldImage->valeur)) {
                Storage::disk('public')->delete($oldImage->valeur);
            }
            
            $filename = 'events-hero-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('', $filename, 'public');
            
            Setting::updateOrCreate(
                ['cle' => 'events_hero_image'],
                ['valeur' => $filename, 'description' => 'Image de fond du hero événements']
            );
        }
        
        return redirect()->route('admin.event-content.index')->with('success', 'Configuration de la page événements mise à jour avec succès !');
    }
}
