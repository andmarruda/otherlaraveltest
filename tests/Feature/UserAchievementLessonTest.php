<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Tests\CommonTrait;
use Illuminate\Support\Facades\Artisan;


class UserAchievementLessonTest extends TestCase
{   
    use RefreshDatabase, CommonTrait;

    private $users;

    /**
     * Set up the test seeding data and choosing five users for test
     */
    public function setUp(): void
    {
        parent::setUp();
        
        Artisan::call('db:seed', [
            '--force' => true,
        ]);

        $this->users = User::whereDoesntHave('comments')->select(['id', 'name', 'email'])->limit(5)->get();
    }

    /**
     * Test when get achievement first lesson watched
     */
    public function test_first_lesson_watched()
    {
        $this->createLessonWatched(1, $this->users[0]);
        $response = $this->get($this->getUrl($this->users[0]->id));
        $response->assertStatus(200);

        [$unlocked_achievements, $next_achievements] = $this->getResponseJsonAchievements($response);
        $this->assertEquals($unlocked_achievements, $this->getUnlockAchievements($this->users[0]->id));
        $this->assertEquals($next_achievements, $this->getNextAvailableAchievements($this->users[0]->id));
    }

    /**
     * Test when get achievement five lessons watched
     */
    public function test_five_lessons_watched()
    {
        $this->createLessonWatched(5, $this->users[1]);
        $response = $this->get($this->getUrl($this->users[1]->id));
        $response->assertStatus(200);

        [$unlocked_achievements, $next_achievements] = $this->getResponseJsonAchievements($response);
        $this->assertEquals($unlocked_achievements, $this->getUnlockAchievements($this->users[1]->id));
        $this->assertEquals($next_achievements, $this->getNextAvailableAchievements($this->users[1]->id));
    }

    /**
     * Test when get achievement ten lessons watched
     */
    public function test_ten_lessons_watched()
    {
        $this->createLessonWatched(10, $this->users[2]);
        $response = $this->get($this->getUrl($this->users[2]->id));
        $response->assertStatus(200);

        [$unlocked_achievements, $next_achievements] = $this->getResponseJsonAchievements($response);
        $this->assertEquals($unlocked_achievements, $this->getUnlockAchievements($this->users[2]->id));
        $this->assertEquals($next_achievements, $this->getNextAvailableAchievements($this->users[2]->id));
    }

    /**
     * Test when get achievement twenty five lessons watched
     */
    public function test_twenty_five_lessons_watched()
    {
        $this->createLessonWatched(25, $this->users[3]);
        $response = $this->get($this->getUrl($this->users[3]->id));
        $response->assertStatus(200);

        [$unlocked_achievements, $next_achievements] = $this->getResponseJsonAchievements($response);
        $this->assertEquals($unlocked_achievements, $this->getUnlockAchievements($this->users[3]->id));
        $this->assertEquals($next_achievements, $this->getNextAvailableAchievements($this->users[3]->id));
    }

    /**
     * Test when get achievement fifty lessons watched
     */
    public function test_fifty_lessons_watched()
    {
        $this->createLessonWatched(50, $this->users[4]);
        $response = $this->get($this->getUrl($this->users[4]->id));
        $response->assertStatus(200);

        [$unlocked_achievements, $next_achievements] = $this->getResponseJsonAchievements($response);
        $this->assertEquals($unlocked_achievements, $this->getUnlockAchievements($this->users[4]->id));
        $this->assertEquals($next_achievements, $this->getNextAvailableAchievements($this->users[4]->id));
    }
}
