<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Boot the trait and listen to model events.
     */
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('created', $model->getAttributes());
        });

        static::updated(function ($model) {
            $oldValues = array_intersect_key($model->getOriginal(), $model->getChanges());
            $newValues = $model->getChanges();
            
            // Remove updated_at from changes if that's the only thing that changed
            unset($oldValues['updated_at']);
            unset($newValues['updated_at']);
            
            if (count($newValues) > 0) {
                $model->logActivity('updated', [
                    'old' => $oldValues,
                    'new' => $newValues,
                ]);
            }
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted', $model->getAttributes());
        });
    }

    /**
     * Record the activity.
     *
     * @param string $action
     * @param array $details
     * @return void
     */
    protected function logActivity(string $action, array $details = [])
    {
        if (!Auth::check()) {
            return; // Don't log if no user is authenticated (e.g., seeding, console)
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => get_class($this),
            'subject_id' => $this->id,
            'details' => $details,
        ]);
    }
}
