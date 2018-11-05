<?php

namespace App\Http\Controllers;

use App\Event;
use App\File;
use App\Http\Requests\MessageRequest;
use App\Message;
use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TopicMessageController extends Controller
{
    /**
     * TopicMessageController constructor.
     */
    public function __construct()
    {
        $this->middleware('member:topic');
    }

    /**
     * @param \App\Topic $topic
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Topic $topic)
    {
        $messages = Message::with('user', 'event', 'files')
            ->whereTopicId($topic->id)->latest()->get();

        $topic->notificationMarkAsRead();

        return response()->make($messages);
    }

    /**
     * @param \App\Topic             $topic
     * @param MessageRequest|Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Topic $topic, MessageRequest $request)
    {
        $message = $this->save($topic, $request);
        $message->load('user', 'event', 'files');

        Auth::user()->notify(Auth::user(), $topic, 'message:created');

        return response()->make($message);
    }

    /**
     * @param \App\Topic               $topic
     * @param \App\Message             $message
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Topic $topic, Message $message, Request $request)
    {
        $message = $this->save($topic, $request, $message);
        $message->load('user', 'event', 'files');

        Auth::user()->notify(Auth::user(), $topic, 'message:updated');

        return response()->make($message);
    }

    /**
     * @param \App\Topic   $topic
     * @param \App\Message $message
     */
    public function destroy(Topic $topic, Message $message)
    {
        if ($message->event) {
            $message->event->delete();
        }

        DB::table('files')->where('type', 'message')
            ->where('parent', $message->id)->delete();

        $message->delete();
    }

    /**
     * @param \App\Topic               $topic
     * @param \Illuminate\Http\Request $request
     * @param \App\Message|null        $message
     *
     * @return \App\Message
     */
    private function save(
        Topic $topic,
        Request $request,
        Message $message = null
    ) {
        if ($message) {
            $message->update($request->only('message'));
        } else {
            $message = new Message($request->only('message'));
        }

        $topic->messages()->save($message);
        $topic->latestMessage()->save($message);

        $event = $request->input('event');
        if (isset($event['title']) && isset($event['start'])) {
            $data = [
                'title'    => $event['title'],
                'topic_id' => $topic->id,
                'start'    => $event['start'],
                'end'      => isset($event['end']) ? $event['end'] : null,
                'location' => isset($event['location']) ? $event['location']
                    : null,
            ];

            // Create new or update existing event
            if ($message->event) {
                $message->event()->update($data);
            } else {
                $message->event()->save(new Event($data));
            }
        }

        // Attach files if we have any

        if ($request->input('files')) {
            $fileIds = array_pluck($request->input('files'), 'id');
            $files = File::whereIn('id', $fileIds)->get();
            $message->files()->saveMany($files);
        }

        return $message;
    }
}
