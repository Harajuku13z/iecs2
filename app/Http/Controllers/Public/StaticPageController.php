<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function show($slug)
    {
        $page = StaticPage::where('slug', $slug)
            ->where('publie', true)
            ->firstOrFail();

        // Récupérer les pages similaires ou les autres pages du même menu
        $pagesSimilaires = StaticPage::where('publie', true)
            ->where('id', '!=', $page->id)
            ->where(function($query) use ($page) {
                if ($page->menu_parent) {
                    $query->where('menu_parent', $page->menu_parent);
                } else {
                    $query->where('menu_parent', $page->menu_nom);
                }
            })
            ->orderBy('menu_ordre')
            ->take(3)
            ->get();

        return view('public.static-page', compact('page', 'pagesSimilaires'));
    }
}

