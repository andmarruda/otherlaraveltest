<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Events\CommentWritten;

class Comment extends Model
{
    use HasFactory;

    /**
     * Making boot event that can set events for insert and delete moments
     */
    /*protected static function boot()
    {
        parent::boot();

        static::created(function ($comment) {
            event(new CommentWritten($comment));
        });

        static::deleted(function ($comment) {
            event(new CommentWritten($comment));
        });
    }*/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body',
        'user_id'
    ];

    /**
     * Get the user that wrote the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
