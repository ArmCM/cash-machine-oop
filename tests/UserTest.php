<?php

namespace App\Tests;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function can_register_a_user()
    {
        $user = new User('John Doe', 'johndoe@gmail.com', '5529889306');

        $this->assertTrue($user->register());
    }

    /** @test */
    public function validates_register_status_when_register_is_true()
    {
        $user = new User('John Doe', 'johndoe@gmail.com', '5529889306');
        $user->register();

        $this->assertTrue($user->statusRegister());
    }

    /** @test */
    public function validates_register_status_when_register_is_false()
    {
        $user = new User('John Doe', 'johndoe@gmail.com', '5529889306');

        $this->assertFalse($user->statusRegister());
    }
}