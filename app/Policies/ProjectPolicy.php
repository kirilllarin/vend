<?php

namespace App\Policies;

use App\Project;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether a user has an access to project
     *
     * @param \App\User $user
     * @param \App\Project $project
     * @return mixed
     */
    public function view(User $user, Project $project)
    {
        return $project->users->contains($user->id);
    }

}
