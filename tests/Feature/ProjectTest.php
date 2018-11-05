<?php

namespace Tests\Feature;

use App\Column;
use App\Project;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\CreatesUser;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use CreatesUser, DatabaseMigrations;

    public function testAdminCanCreateProject()
    {
        $user = $this->createAdmin();

        $project = factory(Project::class)->make([
            'title' => 'My first project',
            'columns' => factory(Column::class, 2)->make(),
            'users' => factory(User::class, 2)->make()
        ]);

        $project->users->add($user);

        $this->actingAs($user)
            ->json('POST', '/api/projects', $project->toArray())
            ->assertStatus(200)
            ->assertJson([
                'title' => 'My first project',
            ]);
    }

    public function testEditorCannotCreateProject()
    {
        $user = factory(User::class)->make(['role' => 'editor']);

        $this->actingAs($user)
            ->post('/api/projects', factory(Project::class)->make()->toArray())
            ->assertStatus(403);
    }

    public function testUserCannotSeeProjectNotMember()
    {
        $user = $this->createEditor();
        $project = factory(Project::class)->create();

        $this->actingAs($user)
            ->json('GET', '/api/projects/'.$project->id)
            ->assertStatus(403);
    }
}