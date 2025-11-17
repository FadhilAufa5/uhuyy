<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Branch extends Model
{
    use LogsActivity;
    protected $fillable = [
        'user_id',
        'file_path',
        'status',
        'images_data',
        'image_gallery',
        'image_path',
        'conversion_status',
    ];

    protected $casts = [
        'images_data' => 'array',
        'image_gallery' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFileNameAttribute(): string
    {
        if ($this->file_path) {
            return basename($this->file_path);
        }
        return 'No file';
    }

    /**
     * Get images from database or fallback to storage
     */
    public function getImagesAttribute(): array
    {
        // Priority 1: Get from database (images_data)
        if (!empty($this->images_data)) {
            return $this->images_data;
        }

        // Priority 2: Fallback to image_gallery (storage paths)
        if (!empty($this->image_gallery)) {
            return array_map(function ($path) {
                return asset('storage/' . $path);
            }, $this->image_gallery);
        }

        return [];
    }

    /**
     * Check if images are stored in database
     */
    public function hasImagesInDatabase(): bool
    {
        return !empty($this->images_data);
    }
}
