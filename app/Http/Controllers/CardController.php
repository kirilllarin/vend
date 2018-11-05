<?php

namespace App\Http\Controllers;

use App\Account;
use App\Card;
use App\Http\Requests\CardRequest;
use App\Project;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    /**
     * CardController constructor.
     */
    public function __construct()
    {
        $this->middleware('role:editor', ['except' => ['index', 'upload']]);
        $this->middleware('member:project');
    }

    /**
     * @param \App\Project $project
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        $cards = Card::whereProjectId($project->id)
            ->with('tasks', 'comments', 'logs', 'logs.user', 'assigned',
                'files')
            ->get();

        return response()->make($cards);
    }

    /**
     * @param \App\Project $project
     * @param \App\Card    $card
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, Card $card)
    {
        $card->load('tasks', 'comments', 'logs', 'logs.user', 'assigned',
            'files');
        $card->notificationMarkAsRead();

        return response()->make($card);
    }

    /**
     * @param \App\Project                   $project
     * @param \App\Http\Requests\CardRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Project $project, CardRequest $request)
    {
        return $this->save($project, $request);
    }

    /**
     * @param \App\Project                   $project
     * @param \App\Http\Requests\CardRequest $request
     * @param \App\Card                      $card
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Project $project, CardRequest $request, Card $card)
    {
        return $this->save($project, $request, $card);
    }

    /**
     * @param \App\Project             $project
     * @param \Illuminate\Http\Request $request
     * @param \App\Card                $card
     *
     * @return \Illuminate\Http\Response
     */
    public function column(Project $project, Request $request, Card $card)
    {
        $card->update($request->all());

        Auth::user()->notify(Auth::user(), $card, 'card:updated');

        return response()->make($card);
    }

    /**
     * @param \App\Project             $project
     * @param \Illuminate\Http\Request $request
     * @param \App\Card|null           $card
     *
     * @return \Illuminate\Http\Response
     */
    private function save(Project $project, Request $request, Card $card = null)
    {

        if (!$card) {
            $data = $request->all();
            $card = Card::create($data);

            // Since it's a new card, we don't need to fetch
            // relations from the db, simply we create empty arrays
            $card->tasks = [];
            $card->files = [];
            $card->logs = [];
            $card->comments = [];
        } else {
            $data = array_only($request->all(), $card->getFillable());
            $card->update($data);
        }

        $card->load('assigned');

        Auth::user()->notify(Auth::user(), $card, 'card:updated');

        return response()->make($card);
    }

    /**
     * @param Project $project
     * @param Card    $card
     */
    public function destroy(Project $project, Card $card)
    {
        $card->delete();
    }

    /**
     * @param Account                  $account
     * @param Project                  $project
     * @param \Illuminate\Http\Request $request
     * @param Card                     $card
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Project $project, Request $request, Card $card)
    {
        $uploadService = new UploadService($card);
        $file = $uploadService->upload($request);

        return response()->make($file);
    }
}
