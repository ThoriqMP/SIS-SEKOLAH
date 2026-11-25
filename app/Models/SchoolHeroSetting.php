<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolHeroSetting extends Model
{
    protected $table = 'school_hero_settings';
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    public static function getValue(string $key, $default = null)
    {
        $s = self::find($key);
        return $s ? $s->value : $default;
    }

    public static function setValue(string $key, ?string $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function getAllSettings(): array
    {
        return self::pluck('value', 'key')->all();
    }
}
