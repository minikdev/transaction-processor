<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

                'amount'=> 'required|integer|gt:0',
                "unpaid_appliance_rates"=> 'required|integer',
                "appliance_rate_cost"=> 'required|integer',
                "tariff_fixed_costs"=> 'required|integer',
                "price_per_kwh"=> 'required|integer'
        ];
    }
}
