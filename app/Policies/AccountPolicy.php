<?php

namespace App\Policies;

use App\Account;
use App\User;

class AccountPolicy
{
    public function view(User $user, Account $account)
    {
        dd('itt');
        return $account->users->find($user->id);
    }

    public function manage(User $user, Account $account)
    {
        $exists = $account->users->find($user->id);
        if( ! $exists) return false;

        $role = $exists->pivot->role;
        return $role == 'admin';
    }
}