<?php

namespace App\Http\Controllers;

use App\Account;
use App\Card;
use App\Project;
use App\Task;
use Illuminate\Http\Request;

class CardTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:editor');
        $this->middleware('member:project');
    }

    /**
     * @param Project $project
     * @param Card    $card
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     * @internal param Account $account
     */
    public function store(Project $project, Card $card, Request $request)
    {
        $task = $card->tasks()->save(new Task($request->all()));

        return response()->make($task);
    }

    /**
     * @param Project $project
     * @param Card    $card
     * @param Task    $task
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     * @internal param Account $account
     */
    public function update(Project $project, Card $card, Task $task, Request $request)
    {
        $task->update($request->all());

        return response()->make($task);
    }

    /**
     * @param Project $project
     * @param Card    $card
     * @param Task    $task
     *
     * @internal param Account $account
     */
    public function destroy(Project $project, Card $card, Task $task)
    {
        $task->delete();
    }
}