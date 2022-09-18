<?php

namespace Tests\Unit\Jobs;

use App\Jobs\NotifyAllVoters;
use App\Mail\IdeaStatusUpdatedMailable;
use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;
use App\Models\Status;
use App\Models\Category;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotifyAllVotersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_sends_an_email_to_all_voters()
    {
        $user = User::factory()->create([
            'email' => 'ann@example.com',
        ]);

        $userB = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusConsidering = Status::factory()->create(['id' => 2, 'name' => 'Considering']);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusConsidering->id,
            'title' => 'My First Idea',
            'description' => 'Description for my first idea',
        ]);

        Vote::create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
        ]);

        Vote::create([
            'idea_id' => $idea->id,
            'user_id' => $userB->id,
        ]);

        Mail::fake();

        NotifyAllVoters::dispatch($idea);

        Mail::assertQueued(IdeaStatusUpdatedMailable::class, function ($mail) {
            return $mail->hasTo('ann@example.com');
            // && $mail->build()->subject === 'An idea you voted for has a new status';
        });
    }
}
