<?php

namespace App\Console\Commands;

use App\Models\PriceAlert;
use App\Traits\SendEmailTrait;
use Illuminate\Console\Command;

class RunAlerts extends Command
{
    use SendEmailTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all alerts from price alerts in db';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $data = $this->fetchPriceAlertsGroupedByUser();
        } catch (\Exception $e) {
            $this->error("Failed to fetch price alerts: {$e->getMessage()}");
            return;
        }

        if (!$data) {
            $this->error("No alerts found!");
            return;
        }

        foreach ($data as $userId => $alerts) {
            $this->info("Running alerts from user: {$userId}\n");
            if (is_null($alerts)) {
                $this->error("No alerts found for {{$userId}");
                break;
            }
            foreach ($alerts as $alert) {
                try {
                    $this->processAlert($alert);
                } catch (\Exception $e) {
                    $this->error("Error processing alert for user {$userId}: {$e->getMessage()}");
                }
            }
        }
    }

    private function fetchPriceAlertsGroupedByUser()
    {
        return PriceAlert::with('product', 'product.priceHistory')->get()->groupBy('created_by');
    }

    private function processAlert($alert)
    {
        if (is_null($alert->product) || is_null($alert->product->priceHistory)) {
            $this->info("Product or price history not found for alert ID {$alert->id}.");
            return;
        }

        $minPriceHistory = $alert->product->priceHistory->last();
        if (is_null($minPriceHistory)) {
            $this->info("No price history available for product {$alert->product->name}.");
            return;
        }

        if ($alert->desired_price >= $minPriceHistory->min_price) {
            $this->info("Product {$alert->product->name} is equal or lower than {$alert->desired_price}. Sending email to {$alert->user->email}");
            $this->sendPriceAlert($alert->user->email, $alert, $minPriceHistory->min_price);
        } else {
            $this->info("Product {$alert->product->name} is not equal or lower than {$alert->desired_price}.");
        }
    }
}
