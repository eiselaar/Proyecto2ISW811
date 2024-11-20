<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\SocialAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_create_post()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('posts.store'), [
            'content' => 'Test post content',
            'platforms' => ['linkedin', 'reddit'],
            'schedule_type' => 'now'
        ]);

        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseHas('posts', [
            'content' => 'Test post content',
            'status' => 'pending'
        ]);
    }

    public function test_user_can_schedule_post()
    {
        $this->actingAs($this->user);

        $scheduledTime = now()->addDay();
        
        $response = $this->post(route('posts.store'), [
            'content' => 'Scheduled post',
            'platforms' => ['linkedin'],
            'schedule_type' => 'scheduled',
            'scheduled_for' => $scheduledTime
        ]);

        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseHas('posts', [
            'content' => 'Scheduled post',
            'status' => 'queued'
        ]);

        $this->assertDatabaseHas('queued_posts', [
            'is_scheduled' => true,
            'scheduled_for' => $scheduledTime->format('Y-m-d H:i:s')
        ]);
    }

    public function test_user_can_cancel_queued_post()
    {
        $this->actingAs($this->user);

        $post = Post::factory()->queued()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->delete(route('posts.destroy', $post));

        $response->assertRedirect();
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}