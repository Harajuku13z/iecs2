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
     * Générer un slug unique à partir du titre
     */
    public static function generateSlug($titre)
    {
        $slug = Str::slug($titre);
        $originalSlug = $slug;
        $counter = 1;

        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Scope pour les pages publiées
     */
    public function scopePublished($query)
    {
        return $query->where('publie', true);
    }

    /**
     * Scope pour les pages à afficher dans le menu
     */
    public function scopeMenuVisible($query)
    {
        return $query->where('afficher_menu', true)->where('publie', true);
    }

    /**
     * Scope pour les pages sans parent (menu principal)
     */
    public function scopeMainMenu($query)
    {
        return $query->whereNull('menu_parent')->orWhere('menu_parent', '');
    }

    /**
     * Scope pour les sous-menus d'un parent
     */
    public function scopeSubMenu($query, $parent)
    {
        return $query->where('menu_parent', $parent);
    }
}

