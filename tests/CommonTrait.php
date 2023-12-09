<?php

namespace Tests;

use App\Models\Achievement;
use App\Models\Comment;
use App\Models\User;
use App\Models\Lesson;

trait CommonTrait {
    const URL = 'users/%d/achievements';

    private function createComment(int $count, int $user_id)
    {
        for($i = 0; $i < $count; $i++) {
            Comment::factory()->create(['user_id' => $user_id]);
        }
    }

    private function createLessonWatched(int $count, User $user)
    {
        $usedLesson = [];
        for($i = 0; $i < $count; $i++) {
            $lesson = Lesson::whereNotIn('id', $usedLesson)->inRandomOrder()->first();
            $user->attachWatched($lesson);
            $usedLesson[] = $lesson->id;
        }
    }

    private function getUnlockAchievements(int $user_id)
    {
        return Achievement::whereHas('user_achievement', function($query) use($user_id) {
            $query->where('user_id', $user_id);
        })->orderBy('model')->orderBy('min_count')->get()->pluck('name')->toArray();
    }

    private function getNextAvailableAchievements(int $user_id)
    {
        return Achievement::whereDoesntHave('user_achievement', function($query) use($user_id) {
            $query->where('user_id', $user_id);
        })->orderBy('model')->orderBy('min_count')->get()->pluck('name')->toArray();
    }

    private function getResponseJsonAchievements($response) : array
    {
        $json = $response->json();
        return [
            $json['unlocked_achievements'], $json['next_available_achievements'],
            $json['current_badge'], $json['next_badge'], $json['remaing_to_unlock_next_badge']
        ];
    }

    private function getUrl(int $user_id)
    {
        return config('app.url').'/'.sprintf(self::URL, $user_id);
    }
}