<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_check_if_user_is_an_admin()
    {
        $user = User::factory()->make([
            'name' => 'Ann',
            'email' => 'ann@example.com',
        ]);

        $userB = User::factory()->make([
            'name' => 'joe',
            'email' => 'joe@example.com',
        ]);

        $this->assertTrue($user->isAdmin());
        $this->assertFalse($userB->isAdmin());
    }
}