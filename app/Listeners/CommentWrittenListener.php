<?php

namespace App\Listeners;

use App\Models\Achievement;
use App\Models\UserAchievement;

class CommentWrittenListener
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = $event->comment->user;
        
        $achievement = Achievement::where('min_count', '=', $user->comments->count())
            ->where('model', get_class($event->comment))
            ->first();

        if($achievement)
        {
            UserAchievement::insertOrIgnore([
                'user_id' => $event->comment->user->id,
                'achievement_id' => $achievement->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $removed = $achievement = UserAchievement::where('user_id', $user->id)
            ->whereHas('achievement', function($query) use ($user, $event) {
                $query->whereRaw('? < min_count', [$user->comments->count()])
                    ->where('model', get_class($event->comment));
            })->delete();
    }
}
