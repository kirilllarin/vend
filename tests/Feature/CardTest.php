<?php

namespace Tests\Feature;

use App\Card;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\CreatesProject;
use Tests\CreatesUser;
use Tests\TestCase;

class CardTest extends TestCase
{
    use CreatesUser, CreatesProject, DatabaseMigrations;

    public function testCreateCard()
    {
        $this->withoutEvents();

        $user = $this->createAdmin();
        $project = $this->createProject($user);

        $card = factory(Card::class)->make([
            'title' => 'Do something special',
            'project_id' => $project->id,
            'column_id' => $project->columns()->first(),
        ]);

        $this->actingAs($user)->json('POST', '/api/projects/' . $project->id . '/cards', $card->toArray())
            ->assertStatus(200)->assertJson([
                'title' => 'Do something special',
            ]);
    }

    public function testShowCard()
    {
        $user = $this->createAdmin();
        $project = $this->createProject($user);

        $card = factory(Card::class)->create([
            'title' => 'Do something special',
            'project_id' => $project->id,
            'column_id' => $project->columns()->first(),
        ]);

        $this->actingAs($user)->json('GET',
                '/api/projects/'.$project->id.'/cards/'.$card->id)->assertStatus(200)->assertJsonStructure([
                'title',
                'tasks' => [],
                'comments' => [],
                'files' => [],
                'logs' => [],
            ]);
    }

    public function testNotMemberCannotCreateCard()
    {
        $user = $this->createAdmin();
        $project = $this->createProject();

        $card = factory(Card::class)->make([
            'project_id' => $project->id,
            'column_id' => $project->columns()->first(),
        ]);

        $this->actingAs($user)->json('POST', '/api/projects/'.$project->id.'/cards',
                $card->toArray())->assertStatus(403);
    }

    public function testClientCannotEditCard()
    {
        $user = $this->createClient();
        $project = $this->createProject($user);

        $card = factory(Card::class)->create([
            'project_id' => $project->id,
            'column_id' => $project->columns()->first()->id,
        ]);

        $this->actingAs($user)->json('PUT', '/api/projects/'.$project->id.'/cards/'.$card->id,
                ['title' => 'New title'])->assertStatus(403);
    }
}
