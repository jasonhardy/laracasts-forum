<?php

namespace App\Traits;

use App\Models\Activity;

trait RecordsActivity
{
    protected static function bootRecordsActivity ()
    {
        if (auth()->guest()) return;

        foreach (static::activitesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    public static function activitesToRecord ()
    {
        return ['created'];
    }

    protected function recordActivity ($event)
    {
        $this->activity()->create([
            'user_id'       => auth()->id(),
            'type'          => $this->getActivityType($event)
        ]);
    }

    public function activity() {
        return $this->morphMany('App\Models\Activity', 'subject');
    }

    public function getActivityType ($event)
    {
        return strtolower(class_basename($this)) . '_' . $event;
    }
}