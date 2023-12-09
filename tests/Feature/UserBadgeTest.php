<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Tests\CommonTrait;
use Illuminate\Support\Facades\Artisan;

class UserBadgeTest extends TestCase
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

        $this->users = User::whereDoesntHave('comments')->select(['id', 'name', 'email'])->limit(7)->get();
    }

    /**
     * Test badge begginer with no achievement
     */
    public function test_no_achievement_begginer()
    {
        $response = $this->get($this->getUrl($this->users[0]->id));
        $response->assertStatus(200);

        [$unlocked_achievements, $next_achievements, $current, $next, $remaining] = $this->getResponseJsonAchievements($response);
        $this->assertEquals($unlocked_achievements, $this->getUnlockAchievements($this->users[0]->id));
        $this->assertEquals($next_achievements, $this->getNextAvailableAchievements($this->users[0]->id));
        $this->assertEquals($current, 'Beginner');
        $this->assertEquals($next, 'Intermediate');
        $this->assertEquals($remaining, 4);
    }

    /**
     * Test badge begginer with one achievement
     */
    public function test_begginer()
    {
        $this->createComment(1, $this->users[1]->id);
        $response = $this->get($this->getUrl($this->users[1]->id));
        $response->assertStatus(200);

        [$unlocked_achievements, $next_achievements, $current, $next, $remaining] = $this->getResponseJsonAchievements($response);
        $this->assertEquals($unlocked_achievements, $this->getUnlockAchievements($this->users[1]->id));
        $this->assertEquals($next_achievements, $this->getNextAvailableAchievements($this->users[1]->id));
        $this->assertEquals($current, 'Beginner');
        $this->assertEquals($next, 'Intermediate');
        $this->assertEquals($remaining, 3);
    }

    /**
     * Test badge intermediate with four achievement
     */
    public function test_intermediate()
    {
        $this->createComment(3, $this->users[2]->id);
        $this->createLessonWatched(5, $this->users[2]);
        $response = $this->get($this->getUrl($this->users[2]->id));
        $response->assertStatus(200);

        [$unlocked_achievements, $next_achievements, $current, $next, $remaining] = $this->getResponseJsonAchievements($response);
        $this->assertEquals($unlocked_achievements, $this->getUnlockAchievements($this->users[2]->id));
        $this->assertEquals($next_achievements, $this->getNextAvailableAchievements($this->users[2]->id));
        $this->assertEquals($current, 'Intermediate');
        $this->assertEquals($next, 'Advanced');
        $this->assertEquals($remaining, 4);
    }

    /**
     * Test badge intermediate with five achievement
     */
    public function test_intermediate_with_five_achievements()
    {
        $this->createComment(5, $this->users[3]->id);
        $this->createLessonWatched(5, $this->users[3]);
        $response = $this->get($this->getUrl($this->users[3]->id));
        $response->assertStatus(200);

        [$unlocked_achievements, $next_achievements, $current, $next, $remaining] = $this->getResponseJsonAchievements($response);
        $this->assertEquals($unlocked_achievements, $this->getUnlockAchievements($this->users[3]->id));
        $this->assertEquals($next_achievements, $this->getNextAvailableAchievements($this->users[3]->id));
        $this->assertEquals($current, 'Intermediate');
        $this->assertEquals($next, 'Advanced');
        $this->assertEquals($remaining, 3);
    }

    /**
     * Test badge advanced with eight achievement
     */
    public function test_advanced()
    {
        $this->createComment(10, $this->users[4]->id);
        $this->createLessonWatched(25, $this->users[4]);
        $response = $this->get($this->getUrl($this->users[4]->id));
        $response->assertStatus(200);

        [$unlocked_achievements, $next_achievements, $current, $next, $remaining] = $this->getResponseJsonAchievements($response);
        $this->assertEquals($unlocked_achievements, $this->getUnlockAchievements($this->users[4]->id));
        $this->assertEquals($next_achievements, $this->getNextAvailableAchievements($this->users[4]->id));
        $this->assertEquals($current, 'Advanced');
        $this->assertEquals($next, 'Master');
        $this->assertEquals($remaining, 2);
    }

    /**
     * Test badge advanced with nine achievement
     */
    public function test_advanced_with_nine_achievement()
    {
        $this->createComment(20, $this->users[5]->id);
        $this->createLessonWatched(25, $this->users[5]);
        $response = $this->get($this->getUrl($this->users[5]->id));
        $response->assertStatus(200);

        [$unlocked_achievements, $next_achievements, $current, $next, $remaining] = $this->getResponseJsonAchievements($response);
        $this->assertEquals($unlocked_achievements, $this->getUnlockAchievements($this->users[5]->id));
        $this->assertEquals($next_achievements, $this->getNextAvailableAchievements($this->users[5]->id));
        $this->assertEquals($current, 'Advanced');
        $this->assertEquals($next, 'Master');
        $this->assertEquals($remaining, 1);
    }

    /**
     * Test badge master
     */
    public function test_master()
    {
        $this->createComment(20, $this->users[6]->id);
        $this->createLessonWatched(50, $this->users[6]);
        $response = $this->get($this->getUrl($this->users[6]->id));
        $response->assertStatus(200);

        [$unlocked_achievements, $next_achievements, $current, $next, $remaining] = $this->getResponseJsonAchievements($response);
        $this->assertEquals($unlocked_achievements, $this->getUnlockAchievements($this->users[6]->id));
        $this->assertEquals($next_achievements, $this->getNextAvailableAchievements($this->users[6]->id));
        $this->assertEquals($current, 'Master');
        $this->assertEquals($next, '');
        $this->assertEquals($remaining, 0);
    }
}
