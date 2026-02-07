<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Highlight Model
 *
 * Represents promotional highlights displayed on the homepage.
 *
 * @property int $id
 * @property string $title
 * @property string|null $image
 * @property string|null $discount_text
 * @property string|null $link
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Highlight extends Model
{
    /** @use HasFactory<\Database\Factories\HighlightFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'image',
        'discount_text',
        'link',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
