<?php

namespace App\Observers;

use App\Models\Reply;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function saved(Reply $reply)
    {
        $reply->topic->increment('reply_count', 1);
    }

    public function creating(Reply $reply){
        $reply->content = clean($reply->content, 'default');
    }
}