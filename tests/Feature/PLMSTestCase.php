<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;
use Modules\User\Entities\User;
use Illuminate\Foundation\Testing\WithFaker;

abstract class PLMSTestCase extends TestCase
{
    use WithFaker;

    protected $user;
    protected $users;
    protected $prefix;
    protected $unauthorizedUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::where('name', 'Super Admin')->first();
        $this->user->assignRole('Super Admin'); // Assign the Super Admin role
        $this->unauthorizedUser = User::inRandomOrder()->where('name','!=','Super Admin')->first();
        $this->prefix = '/api';
        $this->users = User::all();
    }
}
