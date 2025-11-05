<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StaticPage extends Model
{
    protected $fillable = [
        'titre',
        'slug',
        'description',
        'contenu',
        'type_contenu',
        'image_principale',
        'menu_nom',
        'menu_parent',
        'menu_ordre',
        'publie',
        'afficher_menu',
    ];

    protected $casts = [
        'publie' => 'boolean',
        'afficher_menu' => 'boolean',
        'menu_ordre' => 'integer',
    ];

    /**
     * Générer le slug automatiquement si non fourni
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->titre);
            }
        });

        static::updating(function ($page) {
            if ($page->isDirty('titre') && empty($page->slug)) {
                $page->slug = Str::slug($page->titre);
            }
        });
    }

    /**
     * Récupérer les pages pour le menu principal
     */
    public static function getMenuItems()
    {
        return self::where('publie', true)
            ->where('afficher_menu', true)
            ->whereNull('menu_parent')
            ->orderBy('menu_ordre')
            ->orderBy('titre')
            ->get();
    }

    /**
     * Récupérer les sous-menus pour un menu parent
     */
    public function getSubMenus()
    {
        return self::where('publie', true)
            ->where('afficher_menu', true)
            ->where('menu_parent', $this->menu_nom)
            ->orderBy('menu_ordre')
            ->orderBy('titre')
            ->get();
    }

    /**
     * Récupérer tous les noms de menus parents uniques
     */
    public static function getMenuParents()
    {
        return self::where('publie', true)
            ->where('afficher_menu', true)
            ->whereNotNull('menu_nom')
            ->whereNull('menu_parent')
            ->distinct()
            ->pluck('menu_nom')
            ->filter();
    }
}

