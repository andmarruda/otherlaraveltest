<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use App\Events\LessonWatched;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The comments that belong to the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The lessons that a user has access to.
     */
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class);
    }

    /**
     * The lessons that a user has watched.
     */
    public function watched()
    {
        return $this->belongsToMany(Lesson::class)->wherePivot('watched', true);
    }

    /**
     * Attach lesson watched
     * @param Lesson $lesson
     */
    public function attachWatched(Lesson $lesson)
    {
        $this->lessons()->attach($lesson->id, ['watched' => true]);
        event(new LessonWatched($lesson, $this));
    }

    /**
     * Returns the current badge of the user.
     * @return string
     */
    public function actualBadge() : string
    {
        return Badge::where('min_achivement', '<=', $this->achievements->count())
            ->orderBy('min_achivement', 'desc')
            ->limit(1)
            ->first()
            ->name;
    }

    /**
     * Returns nexg badge model
     * @return Badge
     */
    public function nextBadgeModel() : ?Badge
    {
        return Badge::where('min_achivement', '>', $this->achievements->count())
            ->orderBy('min_achivement', 'asc')
            ->limit(1)
            ->first();
    }

    /**
     * Returns the next badge if exists
     * @return string
     */
    public function nextBadge() : string
    {
        $badge = $this->nextBadgeModel();
        return ($badge) ? $badge->name : '';
    }

    /**
     * Returns the remaining achievements to unlock the next badge
     * @return int
     */
    public function remainingToUnlockNextBadge() : int
    {
        $badge = $this->nextBadgeModel();
        return ($badge) ? $badge->min_achivement - $this->achievements->count() : 0;
    }

    /**
     * Returns the achievements of this user
     * @return HasMany
     */
    public function achievements()
    {
        return $this->hasMany(UserAchievement::class);
    }
    
    /**
     * Get all unlocked achievements as array ordered by model and min_count
     * @return array
     */
    public function unlockedAchievements() : array
    {
        return Achievement::whereHas('user_achievement', function($query) {
            $query->where('user_id', $this->id);
        })->orderBy('model')->orderBy('min_count')->get()->pluck('name')->toArray();
    }

    /**
     * Return all next possible achievements as array
     * @return array
     */
    public function nextAvailableAchievements() : array
    {
        return Achievement::whereDoesntHave('user_achievement', function($query) {
            $query->where('user_id', $this->id);
        })->orderBy('model')->orderBy('min_count')->get()->pluck('name')->toArray();
    }
}

