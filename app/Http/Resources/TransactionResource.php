<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'data' => [
                'type' => 'transaction',
                'attributes' => [
                    'paid_for_appliance_rates'=> $this->paid_for_appliance_rates,
                    'fully_covered_appliance_rate'=> $this->fully_covered_appliance_rate,
                    'paid_for_fixed_tariff'=> $this->paid_for_fixed_tariff,
                    'paid_for_energy'=> $this->paid_for_energy,
                    'topup_for_energy'=>$this->topup_for_energy ,
                    'sold_energy'=> $this->sold_energy
                ]
            ]
        ];
    }
}
