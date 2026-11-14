<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Singleton-style record holding RAMStudio's identity and profile.
 */
class AboutProfile extends Model
{
    /** @use HasFactory<\Database\Factories\AboutProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'company_name',
        'tagline',
        'description',
        'vision',
        'mission',
        'logo',
        'hero_image',
        'about_image',
        'address',
        'email',
        'phone',
        'founded_year',
    ];

    protected function casts(): array
    {
        return [
            'founded_year' => 'integer',
        ];
    }

    /**
     * Convenience accessor for the single profile record.
     */
    public static function current(): ?self
    {
        return self::query()->first();
    }
}
