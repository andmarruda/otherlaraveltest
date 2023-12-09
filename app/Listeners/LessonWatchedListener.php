<?php

namespace App\Listeners;

use App\Models\Achievement;
use App\Models\UserAchievement;
use Illuminate\Support\Facades\Log;

class LessonWatchedListener
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $lesson = $event->lesson;
        $user = $event->user;

        $count = Achievement::where('min_count', '=', $user->watched->count())
        ->where('model', get_class($lesson))->count();

        Log::channel('event-log')->info('Achievement count: ' . $count);

        $achievement = Achievement::where('min_count', '=', $user->watched()->count())
            ->where('model', get_class($lesson))
            ->first();

        if($achievement)
        {
            UserAchievement::insertOrIgnore([
                'user_id' => $user->id,
                'achievement_id' => $achievement->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
