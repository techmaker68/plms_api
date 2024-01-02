<?php

namespace Modules\User\Tests\Feature;

use Tests\Feature\PLMSTestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PLMSUserLoginControllerTest extends PLMSTestCase
{
    protected $baseRoute;
    protected $knownPassword = 'secret';
    protected $userTokens = [];

    public function setUp(): void
    {
        parent::setUp();
        $this->baseRoute = '/auth/users';

        foreach ($this->users as $user) {
            $user->update(['password' => Hash::make($this->knownPassword)]);
            $this->userTokens[$user->id] = $user->createToken('PLMS')->accessToken;
        }
    }

    public function test_login()
    {
        foreach ($this->users as $user) {
            $token = $this->userTokens[$user->id];
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->json('POST', $this->prefix . '/auth/login', [
                'email' => $user->email,
                'password' => $this->knownPassword,
            ]);

            $response->assertOk();
        }
    }
}