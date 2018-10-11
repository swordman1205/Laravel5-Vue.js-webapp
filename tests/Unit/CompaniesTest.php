<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompaniesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    // /api/company - Register Company API
    public function testRegisterCompanyExample()
    {
        // $this->assertTrue(true);
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->json('POST', '/api/company', [
                "company_name" => "test company name1",
                "company_address" => "test company address1",
                "typology_id" => 1,
                "sports_id" => "1,3,4,5",
                "registrant_id" => 1
            ]
        );

        $response
                ->assertStatus(200)
                ->assertJson([
                'status' => 'success',
                'result' => [
                    "typology_id" => 1,
                    "company_name" => "test company name1",
                    "company_address" => "test company address1"
                ]
            ]);
    }

    // /api/company.name - Update Company Name API
    public function testUpdateCompanyNameExample()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->json('PUT', '/api/company.name', [
                "company_name" => "updated company name1",
                "company_id" => 1
            ]
        );

        $response
                ->assertStatus(200)
                ->assertJson([
                'status' => 'success',
                'result' => [
                    "id" => 1,
                    "company_name" => "updated company name1"
                ]
            ]);
    }

    // /api/company.address - Update Company Address API
    public function testUpdateCompanyAddressExample()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
        ])->json('PUT', '/api/company.address', [
                "company_address" => "updated company address1",
                "company_id" => 1
            ]
        );

        $response
                ->assertStatus(200)
                ->assertJson([
                'status' => 'success',
                'result' => [
                    "id" => 1,
                    "company_address" => "updated company address1"
                ]
            ]);
    }
}
