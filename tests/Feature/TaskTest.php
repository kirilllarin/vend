<?php

namespace Tests\Feature;

use App\Card;
use App\Comment;
use Tests\CreatesProject;
use Tests\CreatesUser;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TaskTest extends TestCase
{
    use CreatesUser, CreatesProject, DatabaseMigrations;

    public function testAddTaskToCard()
    {
        $this->withoutEvents();

        $user = $this->createEditor();
        $project = $this->createProject($user);

        $card = factory(Card::class)->create([
            'column_id' => $project->columns()->first()->id,
            'project_id' => $project->id,
        ]);

        $task = factory(Comment::class)->make([
            'title' => 'This is the task title',
        ])->toArray();

        $this->actingAs($user)
            ->json('POST', '/api/projects/' . $project->id . '/cards/'.$card->id.'/tasks', $task)
            ->assertStatus(200)
            ->assertJson([
                'title' => 'This is the task title',
            ]);
    }
}
