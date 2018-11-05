<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Column;
use App\Favorite;
use App\Http\Requests\ProjectRequest;
use App\Project;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin', ['only' => ['store', 'update', 'destroy']]);
        $this->middleware('member:project', ['only' => ['show', 'favorite', 'update']]);
    }

    public function index()
    {
        $projects = Project::with('users', 'columns')
            ->withCount('completedCards', 'cards')
            ->where(function ($query) {
                if (request('archive') === '1') {
                    $query->where('is_archive', 1);
                }
            })->whereHas('users', function ($query) {
                $query->where('id', Auth::user()->id);
            })->get();

        return response()->make($projects);
    }

    public function show(Project $project)
    {
        $project->load('users', 'columns');

        return response()->make($project);
    }

    public function store(ProjectRequest $request)
    {
        $project = Project::create($request->all());
        $this->save($project, $request);

        return $this->show($project);
    }

    public function update(Request $request, Project $project)
    {
        $project->update($request->all());
        $this->save($project, $request);

        return $this->show($project);
    }

    public function favorite(Project $project)
    {
        $created = Favorite::toggleFavorite($project);

        return response()->make($created ?: []);
    }

    private function save($project, $request)
    {
        // Sync users
        $userIds = array_pluck($request->input('users'), 'id');
        $project->users()->sync($userIds);

        // Delete columns which not needed
        $toDelete = array_diff(
        	array_pluck($project->columns, 'id'),
	        array_pluck($request->input('columns'), 'id')
        );

        Column::destroy($toDelete);

        $columns = [];
        foreach ($request->input('columns') as $column) {
            if (isset($column['id'])) {
                $c = Column::find($column['id']);
                $c->update($column);
                $columns[] = $c;
            } else {
                $columns[] = new Column($column);
            }
        }

        $project->columns()->saveMany($columns);
    }

    public function destroy(Project $project)
    {
        $project->delete();
    }
}
