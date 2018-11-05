<?php

namespace Tests\Feature;

use App\Card;
use App\Log;
use Carbon\Carbon;
use Tests\CreatesProject;
use Tests\CreatesUser;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LogTest extends TestCase
{
    use DatabaseMigrations, CreatesUser, CreatesProject;

    public function testEditorCannotSeeOthersLog()
    {
        $userEditor = $this->createEditor();
        $userAdmin = $this->createAdmin();
        $project = $this->createProject([$userAdmin, $userEditor]);

        $card = factory(Card::class)->create([
            'project_id' => $project->id,
            'column_id' => $project->columns()->first()->id,
        ]);

        factory(Log::class)->create([
            'card_id' => $card->id,
            'user_id' => $userAdmin->id,
            'length' => 100,
            'created_at' => Carbon::now()->subDays(2)->format('Y-m-d'),
            'updated_at' => Carbon::now()->subDays(2)->format('Y-m-d'),
        ])->toArray();

        $this->actingAs($userEditor)
            ->json('GET', '/api/logs')
            ->assertStatus(200)
            ->assertJson([]);
    }

    public function testEditorCanCreateLog()
    {
        $user = $this->createEditor();
        $project = $this->createProject($user);

        $card = factory(Card::class)->create([
            'project_id' => $project->id,
            'column_id' => $project->columns()->first()->id,
        ]);

        $log = factory(Log::class)->make([
            'card_id' => $card->id,
            'is_running' => 0,
            'length' => 100,
            'date' => Carbon::now()->format('Y-m-d'),
        ])->toArray();

        $this->actingAs($user)
            ->json('POST', '/api/logs', $log)
            ->assertStatus(200)
            ->assertJsonStructure([
                'length',
                'user',
                'card',
            ]);
    }
}
