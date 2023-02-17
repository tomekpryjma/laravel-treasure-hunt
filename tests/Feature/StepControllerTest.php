<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Step;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StepControllerTest extends TestCase
{
    use RefreshDatabase;

    private static $faker;

    public static function setUpBeforeClass(): void
    {
        self::$faker = \Faker\Factory::create();
    }

    public function test_store_validation_fails()
    {
        $this->actingAs(User::factory()->create());

        $response = $this->postJson(route('step.store'));
        $response->assertStatus(422);

        $response = $this->postJson(route('step.store'), [
            'title' => 1111,
            'messages' => ['Foo bar...'],
            'accepted_answers' => ['foo', 'Foo', 'bar'],
        ]);
        $response
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'title' => ['The title must be a string.',]
                ]
            ]);
    }

    public function test_store_validates_and_stores()
    {
        $user = User::factory()->create();
        $game = Game::factory()->for($user)->create();

        $this->actingAs($user);

        $data = [
            'title' => 'My Step',
            'messages' => ['Message 1', 'Message 2'],
            'accepted_answers' => ['answer1', 'Answer1', 'Answer2'],
        ];

        $response = $this->postJson(route('step.store', ['game' => $user->games()->first()]), $data);
        $response->assertStatus(201);

        $this->assertNotNull($game->steps()->where('title', $data['title'])->first());
    }

    public function test_updating_step_title_works()
    {
        $user = User::factory()->create();
        $original = [
            'title' => 'My Step',
            'messages' => ['Message 1', 'Message 2'],
            'accepted_answers' => ['answer1', 'Answer1', 'Answer2'],
        ];
        $step = Step::factory()->for(Game::factory()->for($user))->create($original);

        $replacements = ['title' => 'Updated title'];
        $updated = array_replace($original, $replacements);

        $this->actingAs($user);

        $response = $this->postJson(route('step.update', ['step' => $step]), $updated);
        $response->assertStatus(200);

        $step->refresh();

        $this->assertEquals($step->title, $replacements['title']);
    }

    public function test_updating_step_messages_works()
    {
        $user = User::factory()->create();
        $original = [
            'title' => 'My Step',
            'messages' => ['Message 1', 'Message 2'],
            'accepted_answers' => ['answer1', 'Answer1', 'Answer2'],
        ];
        $step = Step::factory()->for(Game::factory()->for($user))->create($original);

        $replacements = ['messages' => ['Message 1', 'Another message', 'Third message']];
        $updated = array_replace($original, $replacements);

        $this->actingAs($user);

        $response = $this->postJson(route('step.update', ['step' => $step]), $updated);
        $response->assertStatus(200);

        $step->refresh();

        $this->assertEquals(count($updated['messages']), count($step->messages));
        $this->assertEqualsCanonicalizing($updated['messages'], $step->messages);
    }

    public function test_updating_step_accepted_answers_works()
    {
        $user = User::factory()->create();
        $original = [
            'title' => 'My Step',
            'messages' => ['Message 1', 'Message 2'],
            'accepted_answers' => ['answer1', 'Answer1', 'Answer2'],
        ];
        $step = Step::factory()->for(Game::factory()->for($user))->create($original);

        $replacements = ['accepted_answers' => ['Only one answer']];
        $updated = array_replace($original, $replacements);

        $this->actingAs($user);

        $response = $this->postJson(route('step.update', ['step' => $step]), $updated);
        $response->assertStatus(200);

        $step->refresh();

        $this->assertEquals(count($updated['accepted_answers']), count($step->accepted_answers));
        $this->assertEqualsCanonicalizing($updated['accepted_answers'], $step->accepted_answers);
        $this->assertEquals($step->accepted_answers[0], $replacements['accepted_answers'][0]);
    }

    public function test_updating_all_step_fields_works()
    {
        $user = User::factory()->create();
        $original = [
            'title' => 'My Step',
            'messages' => ['Message 1', 'Message 2'],
            'accepted_answers' => ['answer1', 'Answer1', 'Answer2'],
        ];
        $step = Step::factory()->for(Game::factory()->for($user))->create($original);

        $updated = [
            'title' => 'New title',
            'messages' => ['Message One', 'Message Two'],
            'accepted_answers' => ['An answer', 'Another answer'],
        ];

        $this->actingAs($user);

        $response = $this->postJson(route('step.update', ['step' => $step]), $updated);
        $response->assertStatus(200);

        $step->refresh();

        $this->assertEquals($step->title, $updated['title']);
        $this->assertEquals(count($updated['messages']), count($step->messages));
        $this->assertEqualsCanonicalizing($updated['messages'], $step->messages);
        $this->assertEquals(count($updated['accepted_answers']), count($step->accepted_answers));
        $this->assertEqualsCanonicalizing($updated['accepted_answers'], $step->accepted_answers);
    }
}
