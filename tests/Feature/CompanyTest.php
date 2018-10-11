<?php

namespace Tests\Feature;

use App\Company;
use App\Sport;
use App\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Test CompanyController@StoreCompany with proper form data
     */
    public function testSuccessfulStoreCompany()
    {
        $response = $this->json('POST', '/api/companies',
            [
                'typology_id' => 1,
                'sport_id' => 2,
                'user' => [
                    'email' => 'test@test.com',
                ]
            ])
            ->assertStatus(201);
        // assert if user data is properly created
        $user = User::where('email', 'test@test.com')->first();
        $this->assertEquals($user->email, 'test@test.com');

        // assert if company data is properly created
        $company = Company::where('registrant_id', $user->id)->first();
        $this->assertEquals($company->typology_id, 1);
        $this->assertEquals($company->sport_id, 2);
        $this->assertEquals($company->users()->first()->id, $user->id);

        // is user logged in
        $authenticatedUser = Auth::user();
        $this->assertEquals($authenticatedUser->id, $user->id);
    }

    /**
     * Test CompanyController@StoreCompany with email already exists
     */
    public function testEmailExistsStoreCompany()
    {
        User::create([
                'email' => 'test@test.com',
                'password' => 'test']
        );
        $response = $this->json('POST', '/api/companies',
            [
                'typology_id' => 1,
                'sport_id' => 2,
                'user' => [
                    'email' => 'test@test.com',
                ]
            ])->assertStatus(422);
        $this->assertEquals($response->getOriginalContent()['errors']['user.email'][0], 'The user.email has already been taken.');
        // assert that company data is not stored
        $company = Company::first();
        $this->assertNull($company);
    }

    /**
     * Test CompanyController@StoreCompany with no typology_id
     */
    public function testNoTypologyIdStoreCompany()
    {

        $response = $this->json('POST', '/api/companies',
            [
                'name' => 'Company 1',
                'sport_id' => 2,
                'user' => [
                    'name' => 'User Test',
                    'email' => 'test@test.com',
                ]
            ])->assertStatus(422);
        // assert that user data is not stored
        $user = User::where('email', 'test@test.com')->first();
        $this->assertNull($user);
        // assert that company data is not stored
        $company = Company::where('name', 'Company 1')->first();
        $this->assertNull($company);
    }

    /**
     * Test CompanyController@StoreCompany with no sport_id
     */
    public function testNoSportIdStoreCompany()
    {

        $response = $this->json('POST', '/api/companies',
            [
                'name' => 'Company 1',
                'typology_id' => 2,
                'user' => [
                    'name' => 'User Test',
                    'email' => 'test@test.com',
                ]
            ])->assertStatus(422);
        // assert that user data is not stored
        $user = User::where('email', 'test@test.com')->first();
        $this->assertNull($user);
        // assert that company data is not stored
        $company = Company::where('name', 'Company 1')->first();
        $this->assertNull($company);
    }

    /**
     * Test CompanyController@StoreCompany with no user email
     */
    public function testNoUserEmailStoreCompany()
    {

        $response = $this->json('POST', '/api/companies',
            [
                'name' => 'Company 1',
                'typology_id' => 1,
                'sport_id' => 2,
                'user' => [
                    'name' => 'User Test'
                ]
            ])->assertStatus(422);
        // assert that user data is not stored
        $user = User::where('email', 'test@test.com')->first();
        $this->assertNull($user);
        // assert that company data is not stored
        $company = Company::where('name', 'Company 1')->first();
        $this->assertNull($company);
    }

    /**
     * Test CompanyController@UpdateCompanyName with name data
     */
    public function testSuccessfulUpdateCompanyName()
    {
        $user = User::create([
                'name' => 'User Test',
                'email' => 'test@test.com',
                'password' => 'test']
        );
        Auth::loginUsingId($user->id);

        $company = Company::create(
            ['name' => 'Company 1',
                'typology_id' => 1,
                'sport_id' => 1,
                'registrant_id' => 1
            ]);
        $company->users()->attach($user);
        $response = $this->json('PUT', '/api/companies/' . $company->id . '/name',
            [
                'name' => 'Updated name',
            ])
            ->assertStatus(200);

        // assert if company name is properly updated and no other fields are affected
        $company = Company::find($company->id);
        $this->assertEquals($company->name, 'Updated name');
        $this->assertEquals($company->typology_id, 1);
        $this->assertEquals($company->sport_id, 1);
        $this->assertEquals($company->registrant_id, 1);
    }

    /**
     * Test CompanyController@UpdateCompanyName without name data
     */
    public function testFailedUpdateCompanyName()
    {
        $company = Company::create(
            ['name' => 'Company 1',
                'typology_id' => 1,
                'sport_id' => 1,
                'registrant_id' => 1
            ]);

        // Not logged in
        $this->json('PUT', '/api/companies/' . $company->id . '/name',
            [
                'name' => 'Updated name',
            ])
            ->assertStatus(401);

        $user = User::create([
                'name' => 'User Test',
                'email' => 'test@test.com',
                'password' => 'test']
        );
        Auth::loginUsingId($user->id);

        $response = $this->json('PUT', '/api/companies/' . $company->id . '/name',
            [])
            ->assertStatus(422);

        $this->assertEquals($response->getOriginalContent()['errors']['name'][0], 'The name field is required.');

        // assert if company name is not updated and all the fields are the same
        $company = Company::find($company->id);
        $this->assertEquals($company->name, 'Company 1');
        $this->assertEquals($company->typology_id, 1);
        $this->assertEquals($company->sport_id, 1);
        $this->assertEquals($company->registrant_id, 1);
    }

    /**
     * Test CompanyController@UpdateCompanyAddress with address data from Google Maps API
     */
    public function testSuccessfulUpdateCompanyAddress()
    {
        $user = User::create([
                'name' => 'User Test',
                'email' => 'test@test.com',
                'password' => 'test']
        );
        Auth::loginUsingId($user->id);

        $company = Company::create(
            ['name' => 'Company 1',
                'typology_id' => 1,
                'sport_id' => 1,
                'registrant_id' => 1
            ]);
        $company->users()->attach($user);

        $response = $this->json('PUT', '/api/companies/' . $company->id . '/address',
            [
                'address' => 'Via Perrone, 5, 10122 Torino TO, Italy',
                'addressComponents' => json_decode('[{"long_name": "5", "short_name": "5", "types": ["street_number"]},
                                        {"long_name": "Via Perrone", "short_name": "Via Perrone", "types": ["route"]},
                                        {"long_name": "Torino", "short_name": "Torino", "types": ["locality", "political"]},
                                        {"long_name": "Torino", "short_name": "Torino", "types": ["administrative_area_level_3", "political"]},
                                        {"long_name": "Città Metropolitana di Torino", "short_name": "TO", "types":["administrative_area_level_2", "political"]},
                                        {"long_name": "Piemonte", "short_name": "Piemonte", "types": ["administrative_area_level_1", "political"]},
                                        {"long_name": "Italien", "short_name": "IT", "types": ["country", "political"]},
                                        {"long_name": "10122", "short_name": "10122", "types": ["postal_code"]}]'),
                'latitude' => 45.0737146,
                'longitude'=> 7.6734678999999915
            ])
            ->assertStatus(200);
        $company = Company::find($company->id);
        // Not updated fields
        $this->assertEquals($company->name, $company->name);
        $this->assertEquals($company->typology_id, 1);
        $this->assertEquals($company->sport_id, 1);

        // Updated fields
        $this->assertEquals($company->address, 'Via Perrone, 5, 10122 Torino TO, Italy');
        $this->assertEquals($company->google_address, 'Via Perrone');
        $this->assertEquals($company->house_number, '5');
        $this->assertEquals($company->latitude, 45.0737146);
        $this->assertEquals($company->longitude, 7.6734678999999915);
        $this->assertEquals($company->province, 'TO');
        $this->assertEquals($company->region, 'Piemonte');
    }

    /**
     * Test CompanyController@UpdateCompanyAddress without address data
     */
    public function testFailedUpdateCompanyAddress()
    {
        $company = Company::create(
            ['name' => 'Company 1',
                'typology_id' => 1,
                'sport_id' => 1,
                'registrant_id' => 1
            ]);

        //Not logged in
        $response = $this->json('PUT', '/api/companies/' . $company->id . '/address',
            [
                'address' => '123 street, Test, Test',

            ])
            ->assertStatus(401);

        $user = User::create([
                'name' => 'User Test',
                'email' => 'test@test.com',
                'password' => 'test']
        );

        $company->users()->attach($user);

        Auth::loginUsingId($user->id);

        $response = $this->json('PUT', '/api/companies/' . $company->id . '/address',
            [])
            ->assertStatus(422);
        $this->assertEquals($response->getOriginalContent()['errors']['address'][0], 'The address field is required.');

        // assert if company address is not updated and all the fields are the same
        $company = Company::find($company->id);
        $this->assertNull($company->address);
        $this->assertEquals($company->name, $company->name);
        $this->assertEquals($company->typology_id, 1);
        $this->assertEquals($company->sport_id, 1);
        $this->assertEquals($company->registrant_id, 1);
    }

    /**
     * Test CompanyController@AddSports with valid sport ids
     */
    public function testSuccessfulAddSports()
    {
        $user = User::create([
                'name' => 'User Test',
                'email' => 'test@test.com',
                'password' => 'test']
        );
        Auth::loginUsingId($user->id);

        $company = Company::create(
            ['name' => 'Company 1',
                'typology_id' => 1,
                'sport_id' => 1,
                'registrant_id' => 1
            ]);
        $company->users()->attach($user);

        $sport1 = Sport::create(array('name' => 'sport 1'));
        $sport2 = Sport::create(array('name' => 'sport 2'));
        $sport3 = Sport::create(array('name' => 'sport 3'));

        $sportsToAttach[0]['id'] = $sport1->id;
        $sportsToAttach[1]['id'] = $sport2->id;
        $response = $this->json('POST', '/api/companies/' . $company->id . '/sports',
            [
                'sports' => $sportsToAttach,
            ])
            ->assertStatus(200);

        $company = Company::find($company->id);
        // Linked sports
        $this->assertEquals($company->sports()->pluck('sports.id')->toArray(), array_column($sportsToAttach, 'id'));
    }

    /**
     * Test CompanyController@AddSports with invalid sport ids
     */
    public function testFailedAddSports()
    {
        $company = Company::create(
            ['name' => 'Company 1',
                'typology_id' => 1,
                'sport_id' => 1,
                'registrant_id' => 1
            ]);

        // Not logged in
        $this->json('POST', '/api/companies/' . $company->id . '/sports',
            [
                'sports' => [],
            ])
            ->assertStatus(401);

        $user = User::create([
                'name' => 'User Test',
                'email' => 'test@test.com',
                'password' => 'test']
        );
        Auth::loginUsingId($user->id);
        $company->users()->attach($user);

        $this->json('POST', '/api/companies/' . $company->id . '/sports',
            [
                'sports' => [],
            ])
            ->assertStatus(422);

        $sport1 = Sport::create(array('name' => 'sport 1'));

        // Send sport id not as array
        $this->json('POST', '/api/companies/' . $company->id . '/sports',
            [
                'sports' => $sport1->id,
            ])
            ->assertStatus(422);
    }
}
