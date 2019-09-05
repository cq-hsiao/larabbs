<?php

namespace App\Observers;

use App\Models\Topic;
use http\Message;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    public function saving(Topic $topic)
    {
        $topic->body = clean($topic->body, 'user_topic_body'); // 过滤，防止XSS攻击
        if(empty(trim($topic->body)))
        {
            return false;
        }
        $topic->excerpt = make_excerpt($topic->body);
    }
}