<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_create_schedule()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('schedules.store'), [
            'day_of_week' => 1,
            'time' => '09:00',
            'is_active' => true
        ]);

        $response->assertRedirect(route('schedules.index'));
        $this->assertDatabaseHas('schedules', [
            'user_id' => $this->user->id,
            'day_of_week' => 1,
            'time' => '09:00:00'
        ]);
    }

    public function test_user_can_delete_schedule()
    {
        $this->actingAs($this->user);

        $schedule = Schedule::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->delete(route('schedules.destroy', $schedule));

        $response->assertRedirect(route('schedules.index'));
        $this->assertDatabaseMissing('schedules', ['id' => $schedule->id]);
    }
}