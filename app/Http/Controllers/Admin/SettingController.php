<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->except(['_token', 'logo', 'admission_process_image']) as $cle => $valeur) {
            Setting::where('cle', $cle)->update(['valeur' => $valeur]);
        }

        // Gestion de l'upload du logo
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo-' . time() . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('public')->putFileAs('', $file, $filename);
            Setting::updateOrCreate(
                ['cle' => 'logo'],
                ['valeur' => $filename, 'description' => 'Logo de l\'IESCA']
            );
        }

        // Gestion de l'upload de l'image du processus d'admission
        if ($request->hasFile('admission_process_image')) {
            $file = $request->file('admission_process_image');
            $filename = 'admission-process-' . time() . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('public')->putFileAs('', $file, $filename);
            Setting::updateOrCreate(
                ['cle' => 'admission_process_image'],
                ['valeur' => $filename, 'description' => 'Image du processus d\'admission (format 9:16)']
            );
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Paramètres mis à jour avec succès.');
    }
}
