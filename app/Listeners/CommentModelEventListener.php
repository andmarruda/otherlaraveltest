<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Comment;
use App\Events\CommentWritten;

class CommentModelEventListener
{
    public function created(Comment $comment)
    {
        event(new CommentWritten($comment));
    }

    public function deleted(Comment $comment)
    {
        event(new CommentWritten($comment));
    }
}