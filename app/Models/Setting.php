<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['cle', 'valeur', 'description'];

    public static function get($key, $default = null)
    {
        $setting = self::where('cle', $key)->first();
        return $setting ? $setting->valeur : $default;
    }

    public static function set($key, $value, $description = null)
    {
        return self::updateOrCreate(
            ['cle' => $key],
            ['valeur' => $value, 'description' => $description]
        );
    }
}
