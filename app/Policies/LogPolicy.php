<?php

namespace App\Policies;

use App\Log;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogPolicy
{
    use HandlesAuthorization;

    public function destroy(User $user, Log $log)
    {
        return $log->user_id === $user->id || $user->isAdmin();
    }
}
