<?php
namespace App\Http\Services;

class TransactionService{

    public function process($transaction)
    {
        $processedApplianceRateResult = $this->processApplianceRates($transaction);
        $paidForFixedTariff = $this->processFixedTarif($transaction);

        $energyPaymentResult = $this->processEnergyPayment($transaction);
        return (object)collect([
            "paid_for_appliance_rates"=>$processedApplianceRateResult["paid_for_appliance_rates"],
            "fully_covered_appliance_rate"=>$processedApplianceRateResult["fully_covered_appliance_rate"],
            "paid_for_fixed_tariff"=>$paidForFixedTariff,
            "paid_for_energy"=>$energyPaymentResult["paid_for_energy"],
            "topup_for_energy"=>$energyPaymentResult["topup_for_energy"],
            "sold_energy"=>$energyPaymentResult["sold_energy"]
            ])->all();
    }
    private function processEnergyPayment($transaction)
    {
        $actualEnerygyWorth = $transaction->amount/$transaction->price_per_kwh;

        $sold_energy =ceil($actualEnerygyWorth*10)/10;

        $paid_for_energy = $sold_energy * $transaction->price_per_kwh;

        $top_up_energy= $paid_for_energy - $transaction->amount;
        return ["sold_energy"=>$sold_energy,"paid_for_energy"=>round($paid_for_energy),"topup_for_energy"=>round($top_up_energy)];
    }
    private function processFixedTarif($transaction)
    {
        $paid_for_fixed_tariff=0;
        if ($transaction->amount - $transaction->tariff_fixed_costs < 0) {
            $paid_for_fixed_tariff= $transaction->amount;
            $transaction->amount = 0;
        }else{
            $paid_for_fixed_tariff= $transaction->tariff_fixed_costs;
            $transaction->amount -=$transaction->tariff_fixed_costs;
        }
        return round($paid_for_fixed_tariff);
    }
    private function processApplianceRates($transaction)
    {

        if ($transaction->unpaid_appliance_rates<=0){
            return ["paid_for_appliance_rates"=>0,"fully_covered_appliance_rate"=>0];
        }

        $index = 0;
        while ($index <$transaction->unpaid_appliance_rates && $transaction->amount>=$transaction->appliance_rate_cost) {
            $transaction->amount -= $transaction->appliance_rate_cost;
            $index++;

        }
        $coveredApplianceRates = $index * $transaction->appliance_rate_cost;
        $coveredApplianceRateCount= $index;
        return ["paid_for_appliance_rates"=>$coveredApplianceRates,"fully_covered_appliance_rate"=>$coveredApplianceRateCount];
    }
}
