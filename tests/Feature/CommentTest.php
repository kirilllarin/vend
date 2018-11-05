<?php

namespace Tests\Feature;

use App\Card;
use App\Comment;
use Tests\CreatesProject;
use Tests\CreatesUser;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CommentTest extends TestCase
{
    use CreatesUser, CreatesProject, DatabaseMigrations;

    public function testAddCommentToCard()
    {
        $this->withoutEvents();

        $user = $this->createEditor();
        $project = $this->createProject($user);

        $card = factory(Card::class)->create([
            'column_id' => $project->columns()->first()->id,
            'project_id' => $project->id,
        ]);

        $comment = factory(Comment::class)->make([
            'content' => 'This is the comment content',
            'user_id' => $user->id,
        ])->toArray();

        $this->actingAs($user)
            ->json('POST', '/api/projects/' . $project->id . '/cards/' . $card->id . '/comments', $comment)
            ->assertStatus(200)
            ->assertJson([
                'content' => 'This is the comment content',
            ]);
    }

    public function testCannotDeleteOthersComment()
    {
        $this->withoutEvents();

        $userEditor = $this->createEditor();
        $userAdmin = $this->createAdmin();
        $project = $this->createProject([$userAdmin, $userEditor]);

        $card = factory(Card::class)->create([
            'column_id' => $project->columns()->first()->id,
            'project_id' => $project->id,
        ]);

        $this->actingAs($userAdmin);

        $comment = factory(Comment::class)->make([
            'content' => 'This is the comment content',
        ]);

        $card->comments()->save($comment);

        $this->actingAs($userEditor)
            ->json('DELETE', '/api/projects/' . $card->project_id . '/cards/' . $card->id . '/comments/' . $comment->id)
            ->assertStatus(403);
    }
}
