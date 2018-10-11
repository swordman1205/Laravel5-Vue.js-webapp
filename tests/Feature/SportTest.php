<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Auth;
use PopulateSports;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test SportController@searchSport with query = football
     */
    public function testSuccessfulSearchSport()
    {
        $seeder = new PopulateSports();
        $seeder->run();

        $user = User::create([
                'name' => 'User Test',
                'email' => 'test@test.com',
                'password' => 'test']
        );
        Auth::loginUsingId($user->id);

        $response = $this->json('GET', '/api/sports/search',
            [
                'q' => 'football'
            ])
            ->assertStatus(200);
        $content = $response->getOriginalContent();

        $this->assertEquals($content['sports'][0]['name'], 'American Football');
        $this->assertEquals($content['sports'][1]['name'], 'Football');
    }
}
