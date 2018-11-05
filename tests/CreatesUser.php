<?php

namespace Tests;

use App\User;

trait CreatesUser
{
    public function createAdmin()
    {
        return $this->createUser(['role' => 'admin']);
    }

    public function createClient()
    {
        return $this->createUser(['role' => 'client']);
    }

    public function createEditor()
    {
        return $this->createUser(['role' => 'editor']);
    }

    public function createUser($attributes = [])
    {
        return factory(User::class)->create($attributes);
    }
}