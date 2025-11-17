<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'event',
        'model_type',
        'model_id',
        'description',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getModelNameAttribute(): string
    {
        if (!$this->model_type) {
            return '-';
        }

        return class_basename($this->model_type);
    }

    public function getEventBadgeColorAttribute(): string
    {
        return match($this->event) {
            'created' => 'green',
            'updated' => 'blue',
            'deleted' => 'red',
            'logged_in' => 'lime',
            'logged_out' => 'gray',
            'restored' => 'purple',
            'uploaded' => 'sky',
            default => 'zinc',
        };
    }

    public function getEventIconAttribute(): string
    {
        return match($this->event) {
            'created' => 'plus-circle',
            'updated' => 'pencil',
            'deleted' => 'trash',
            'logged_in' => 'arrow-right-end-on-rectangle',
            'logged_out' => 'arrow-left-start-on-rectangle',
            'restored' => 'arrow-path',
            'uploaded' => 'arrow-up-tray',
            default => 'information-circle',
        };
    }
}
