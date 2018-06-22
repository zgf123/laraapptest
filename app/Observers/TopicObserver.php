<?php

namespace App\Observers;

use App\Models\Topic;
use App\Jobs\TranslateSlug;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function saving(Topic $topic){
        $topic->body = clean($topic->body, 'default');

        $topic->excerpt = make_excerpt($topic->body);
        
    }

    public function saved(Topic $topic){
        if( empty($topic->slug) ){
            dispatch( new TranslateSlug($topic) );
        }
    }
}