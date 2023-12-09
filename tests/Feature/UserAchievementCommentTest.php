<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Tests\CommonTrait;
use Illuminate\Support\Facades\Artisan;

class UserAchievementCommentTest extends TestCase
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
     * Test when get achievement first comment
     */
    public function test_first_comment()
    {
        $this->createComment(1, $this->users[0]->id);
        $response = $this->get($this->getUrl($this->users[0]->id));
        $response->assertStatus(200);

        [$unlocked_achievements, $next_achievements] = $this->getResponseJsonAchievements($response);
        $this->assertEquals($unlocked_achievements, $this->getUnlockAchievements($this->users[0]->id));
        $this->assertEquals($next_achievements, $this->getNextAvailableAchievements($this->users[0]->id));
    }

    /**
     * Test when get achievement three comments
     */
    public function test_three_comments()
    {
        $this->createComment(3, $this->users[1]->id);
        $response = $this->get($this->getUrl($this->users[1]->id));
        $response->assertStatus(200);

        [$unlocked_achievements, $next_achievements] = $this->getResponseJsonAchievements($response);
        $this->assertEquals($unlocked_achievements, $this->getUnlockAchievements($this->users[1]->id));
        $this->assertEquals($next_achievements, $this->getNextAvailableAchievements($this->users[1]->id));
    }

    /**
     * Test when get achievement five comments
     */
    public function test_five_comments()
    {
        $this->createComment(5, $this->users[2]->id);
        $response = $this->get($this->getUrl($this->users[2]->id));
        $response->assertStatus(200);

        [$unlocked_achievements, $next_achievements] = $this->getResponseJsonAchievements($response);
        $this->assertEquals($unlocked_achievements, $this->getUnlockAchievements($this->users[2]->id));
        $this->assertEquals($next_achievements, $this->getNextAvailableAchievements($this->users[2]->id));
    }

    /**
     * Test when get achievement ten comments
     */
    public function test_ten_comments()
    {
        $this->createComment(10, $this->users[3]->id);        
        $response = $this->get($this->getUrl($this->users[3]->id));
        $response->assertStatus(200);

        [$unlocked_achievements, $next_achievements] = $this->getResponseJsonAchievements($response);
        $this->assertEquals($unlocked_achievements, $this->getUnlockAchievements($this->users[3]->id));
        $this->assertEquals($next_achievements, $this->getNextAvailableAchievements($this->users[3]->id));
    }

    /**
     * Test when get achievement twenty comments
     */
    public function test_twenty_comments()
    {
        $this->createComment(20, $this->users[4]->id);
        $response = $this->get($this->getUrl($this->users[4]->id));
        $response->assertStatus(200);

        [$unlocked_achievements, $next_achievements] = $this->getResponseJsonAchievements($response);
        $this->assertEquals($unlocked_achievements, $this->getUnlockAchievements($this->users[4]->id));
        $this->assertEquals($next_achievements, $this->getNextAvailableAchievements($this->users[4]->id));
    }
}
