<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    /** @use HasFactory<\Database\Factories\SettingFactory> */
    use HasFactory;

    /** Cache key used by the {@see self::get()} helper. */
    private const CACHE_KEY = 'settings.all';

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
    ];

    /**
     * Retrieve a setting value by its key, falling back to the default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = Cache::rememberForever(self::CACHE_KEY, function () {
            return self::query()->pluck('value', 'key')->all();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * Persist a setting value, updating the cached map afterwards.
     */
    public static function set(string $key, ?string $value, string $group = 'general', string $type = 'text'): self
    {
        $setting = self::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group, 'type' => $type],
        );

        Cache::forget(self::CACHE_KEY);

        return $setting;
    }

    public static function flushCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    protected static function booted(): void
    {
        static::saved(fn() => Cache::forget(self::CACHE_KEY));
        static::deleted(fn() => Cache::forget(self::CACHE_KEY));
    }
}
