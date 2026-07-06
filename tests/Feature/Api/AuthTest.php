<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_returns_token()
    {
        $user = User::create([
            'username' => 'testuser',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'username' => 'testuser',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['user', 'token'],
            ]);
    }

    public function test_logout_requires_authentication()
    {
        $user = User::create([
            'username' => 'testuser',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);

        $token = $user->createToken('mobile-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/auth/logout');

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'message' => 'Logged out',
                'data' => null,
            ]);
    }
}
