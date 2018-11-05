<?php

namespace Tests;

use App\Column;
use App\Project;
use App\User;

trait CreatesProject
{
    public function createProject($user = null)
    {
        $project = factory(Project::class)->create();
        $users = factory(User::class, 2)->make();

        if ($user) {
            if(is_array($user)) {
                foreach($user as $u) {
                    $users->add($u);
                };
            } else {
                $users->add($user);
            }
        }

        $project->users()->saveMany($users);
        $project->columns()->saveMany(factory(Column::class, 2)->make());

        return $project;
    }
}