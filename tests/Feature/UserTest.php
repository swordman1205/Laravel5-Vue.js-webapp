<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test UserController@updatePassword with proper password
     */
    public function testSuccessfulUpdatePassword()
    {
        $user = User::create([
                'name' => 'User Test',
                'email' => 'test@test.com',
                'password' => 'test']
        );
        Auth::loginUsingId($user->id);

        $response = $this->json('PUT', '/api/users/'.$user->id.'/password',
            [
                'password' => '123456'
            ])
            ->assertStatus(200);

        // login with new password
        $this->assertTrue(Auth::attempt(['email' => $user->email, 'password' => '123456']));
    }

    /**
     * Test UserController@updatePassword with invalid password
     */
    public function testFailedUpdatePassword()
    {
        $user = User::create([
                'name' => 'User Test',
                'email' => 'test@test.com',
                'password' => 'test']
        );

        // User not logged in
        $response = $this->json('PUT', '/api/users/'.$user->id.'/password',
            [
                'password' => '12345'
            ])
            ->assertStatus(401);

        Auth::loginUsingId($user->id);

        // Password to short
        $response = $this->json('PUT', '/api/users/'.$user->id.'/password',
            [
                'password' => '12345'
            ])
            ->assertStatus(422);
        // Failed login with invalid password
        $this->assertFalse(Auth::attempt(['email' => $user->email, 'password' => '12345']));
    }
}
