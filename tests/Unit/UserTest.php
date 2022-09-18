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
            'email' => 'ann@example.com',
        ]);

        $userB = User::factory()->make([
            'email' => 'joe@example.com',
        ]);

        $this->assertTrue($user->isAdmin());
        $this->assertFalse($userB->isAdmin());
    }
}