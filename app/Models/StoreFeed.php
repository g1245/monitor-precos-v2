<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * StoreFeed Model
 *
 * Represents a product feed from a store.
 *
 * @property int $id
 * @property int $store_id
 * @property string|null $source
 * @property string $download_url
 * @property bool $has_pending_update
 * @property \Illuminate\Support\Carbon|null $last_updated_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class StoreFeed extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'store_id',
        'source',
        'download_url',
        'has_pending_update',
        'last_updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_updated_at' => 'datetime',
        'has_pending_update' => 'boolean',
    ];

    /**
     * Get the store that owns this feed.
     *
     * @return BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the local file path for this feed.
     *
     * @return string
     */
    public function getLocalFilePath(): string
    {
        return "private/store_feeds/feed_{$this->id}.csv";
    }

    /**
     * Get the full local file path for this feed.
     *
     * @return string
     */
    public function getFullLocalFilePath(): string
    {
        return storage_path("app/{$this->getLocalFilePath()}");
    }
}
