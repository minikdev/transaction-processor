<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_valid_data()
    {



        $data=["amount"=> 1000,
        "unpaid_appliance_rates"=> 2,
        "appliance_rate_cost"=> 200,
        "tariff_fixed_costs"=> 160,
        "price_per_kwh"=> 700
        ];

        $response = $this->post('/api/transactions', $data);
        // $response->assertStatus(200);
        $response->assertJson( ["data"=> [
            "type"=> "transaction",
            "attributes"=> [
                "paid_for_appliance_rates"=> 400,
                "fully_covered_appliance_rate"=> 2,
                "paid_for_fixed_tariff"=> 160,
                "paid_for_energy"=> 490,
                "topup_for_energy"=> 50,
                "sold_energy"=> 0.7
                ]
                ]]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_amount_less_than_rate_cost()
    {



        $data=["amount"=> 100,
        "unpaid_appliance_rates"=> 2,
        "appliance_rate_cost"=> 200,
        "tariff_fixed_costs"=> 160,
        "price_per_kwh"=> 700
        ];

        $response = $this->post('/api/transactions', $data);
        // $response->assertStatus(200);
        $response->assertJson( ["data"=> [
            "type"=> "transaction",
            "attributes"=> [
                "paid_for_appliance_rates"=> 0,
                "fully_covered_appliance_rate"=> 0,
                "paid_for_fixed_tariff"=> 100,
                "paid_for_energy"=> 0,
                "topup_for_energy"=> 0,
                "sold_energy"=> 0
                ]
                ]]);
    }
    public function test_covered_appliance_rate()
    {



        $data=["amount"=> 300,
        "unpaid_appliance_rates"=> 2,
        "appliance_rate_cost"=> 200,
        "tariff_fixed_costs"=> 160,
        "price_per_kwh"=> 700
        ];

        $response = $this->post('/api/transactions', $data);
        // $response->assertStatus(200);
        $response->assertJson( ["data"=> [
            "type"=> "transaction",
            "attributes"=> [
                "paid_for_appliance_rates"=> 200,
                "fully_covered_appliance_rate"=> 1,
                "paid_for_fixed_tariff"=> 100,
                "paid_for_energy"=> 0,
                "topup_for_energy"=> 0,
                "sold_energy"=> 0
                ]
                ]]);
    }
    public function test_amount_less_than_zero()
    {



        $data=["amount"=> -500,
        "unpaid_appliance_rates"=> 2,
        "appliance_rate_cost"=> 200,
        "tariff_fixed_costs"=> 160,
        "price_per_kwh"=> 700
        ];

        $response = $this->post('/api/transactions', $data);
        // $response->assertStatus(200);
        $response->assertJson( ["data"=> [
            "message"=>"The given data was invalid.",
            "status_code"=>400
            ]
        ]);
    }
}
