<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->except(['_token', 'logo']) as $cle => $valeur) {
            Setting::where('cle', $cle)->update(['valeur' => $valeur]);
        }

        // Gestion de l'upload du logo
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public', $filename);
            Setting::where('cle', 'logo')->update(['valeur' => $filename]);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Paramètres mis à jour avec succès.');
    }
}
