<?php

namespace App\Http\Controllers;

use App\File;
use App\Topic;

class TopicFilesController extends Controller
{
    /**
     * @param Topic $topic
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Topic $topic)
    {
        $files = File::whereHas('message', function ($query) use ($topic) {
            $query->where('topic_id', $topic->id);
        })->get();

        return response()->make($files);
    }
}
