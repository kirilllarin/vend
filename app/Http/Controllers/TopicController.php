<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Http\Requests\TopicRequest;
use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    /**
     * TopicController constructor.
     */
    public function __construct()
    {
        $this->middleware('role:editor',
            ['only' => ['store', 'update', 'destroy']]);
        $this->middleware('member:topic', ['except' => ['store', 'index']]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topics = Topic::whereHas('users', function ($query) {
            $query->where('topic_user.user_id', Auth::user()->id);
        })
            ->with('latestMessage', 'latestMessage.user', 'users')
            ->get();

        return response()->make($topics);
    }

    /**
     * @param \App\Topic $topic
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Topic $topic)
    {
        $topic->load('latestMessage', 'users');

        return response()->make($topic);
    }

    /**
     * @param \App\Http\Requests\TopicRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TopicRequest $request)
    {
        $topic = Topic::create($request->all());
        $this->attachUsers($topic, $request);

        return $this->show($topic);
    }

    /**
     * @param \App\Http\Requests\TopicRequest $request
     * @param \App\Topic                      $topic
     *
     * @return \Illuminate\Http\Response
     */
    public function update(TopicRequest $request, Topic $topic)
    {
        $topic->update($request->all());
        $this->attachUsers($topic, $request);

        return $this->show($topic);
    }

    /**
     * @param \App\Topic $topic
     *
     * @return void
     * @throws \Exception
     */
    public function destroy(Topic $topic)
    {
        $topic->delete();
    }

    /**
     * @param Topic $topic
     *
     * @return \Illuminate\Http\Response
     */
    public function favorite(Topic $topic)
    {
        $created = Favorite::toggleFavorite($topic);

        return response()->make($created ?: []);
    }

    /**
     * @param \App\Topic               $topic
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    private function attachUsers(Topic $topic, Request $request)
    {
        $users = array_pluck($request->input('users'), 'id');

        if (!in_array(Auth::user()->id, $users, true)) {
            $users[] = Auth::user()->id;
        }

        $topic->users()->sync($users);
    }
}
