<?php

namespace Tests\Feature\Comments;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Comment;
use App\Http\Livewire\MarkCommentAsSpam;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentsSpamManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shows_mark_as_spam_livewire_component_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
            'body' => 'This is my first comment',
        ]);

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertSeeLivewire('mark-comment-as-spam');
    }

    /** @test */
    public function does_not_show_mark_comment_as_spam_livewire_component_when_user_does_not_have_authorization()
    {
        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'body' => 'This is mu first comment',
        ]);

        $this->get(route('idea.show', $idea))
            ->assertDontSeeLivewire('mark-comment-as-spam');
    }

    /** @test */
    public function marking_a_comment_as_spam_works_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $comment = Comment::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
            'body' => 'This is my first comment',
        ]);

        Livewire::actingAs($user)
            ->test(MarkCommentAsSpam::class)
            ->call('setMarkAsSpamComment', $comment->id)
            ->call('markAsSpam')
            ->assertEmitted('commentWasMarkedAsSpam');

        $this->assertEquals(1, Comment::first()->spam_reports);
    }
}