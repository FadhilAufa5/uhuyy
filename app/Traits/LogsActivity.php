<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            static::logActivity($model, 'created', 'Created ' . class_basename($model));
        });

        static::updated(function ($model) {
            static::logActivity($model, 'updated', 'Updated ' . class_basename($model));
        });

        static::deleted(function ($model) {
            static::logActivity($model, 'deleted', 'Deleted ' . class_basename($model));
        });
    }

    protected static function logActivity($model, string $event, string $description): void
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();
        $properties = [];

        if ($event === 'updated') {
            $properties = [
                'old' => $model->getOriginal(),
                'new' => $model->getAttributes(),
            ];
        } elseif ($event === 'deleted') {
            $properties = [
                'deleted' => $model->getAttributes(),
            ];
        } elseif ($event === 'created') {
            $properties = [
                'created' => $model->getAttributes(),
            ];
        }

        ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'event' => $event,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function logCustomActivity(string $event, string $description, array $properties = []): void
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'event' => $event,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
