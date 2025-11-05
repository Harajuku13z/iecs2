<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    /**
     * Afficher une page statique
     */
    public function show($slug)
    {
        $page = StaticPage::where('slug', $slug)->published()->firstOrFail();
        
        return view('public.static-page', compact('page'));
    }
}

