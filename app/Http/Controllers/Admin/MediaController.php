<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function images(Request $request)
    {
        $extensions = ['jpg','jpeg','png','gif','webp','svg'];
        $allFiles = Storage::disk('public')->allFiles();
        $files = [];
        foreach ($allFiles as $path) {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if (in_array($ext, $extensions)) {
                $files[] = [
                    'path' => $path,
                    'url' => asset('storage/' . $path),
                    'name' => basename($path),
                ];
            }
        }

        return response()->json([ 'files' => $files ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $path = $request->file('file')->store('uploads', 'public');

        return response()->json([
            'success' => true,
            'file' => [
                'path' => $path,
                'url' => asset('storage/' . $path),
                'name' => basename($path),
            ],
        ]);
    }
}


