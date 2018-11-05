<?php

namespace App\Policies;

use App\Account;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function admin(User $user)
    {
        return $user->isAdmin();
    }
}
