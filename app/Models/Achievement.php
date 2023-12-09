<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;
    protected $fillable = ['model', 'name', 'min_count'];

    /**
     * Returns users that have this achievement
     */
    public function user_achievement()
    {
        return $this->hasMany(UserAchievement::class);
    }
}
