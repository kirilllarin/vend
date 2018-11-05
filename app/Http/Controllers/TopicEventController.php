<?php

namespace App\Http\Controllers;

use App\Event;
use App\Topic;

class TopicEventController extends Controller
{
    /**
     * @param Topic $topic
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Topic $topic)
    {
        $events = Event::with('message', 'message.user')
            ->where('topic_id', $topic->id)
            ->whereRaw('start >= CURDATE()')
            ->orderBy('start', 'asc')
            ->get();

        return response()->make($events);
    }

}
