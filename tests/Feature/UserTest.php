<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\CreatesUser;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations, CreatesUser;

    public function testEditorCannotEditUser()
    {
        $this->withoutEvents();

        $user = factory(User::Class)->create();
        $userEditor = $this->createEditor();

        $this->actingAs($userEditor)
            ->json('PUT', '/api/users/'.$user->id, ['name' => 'Cheating'])
            ->assertStatus(403);
    }
}
