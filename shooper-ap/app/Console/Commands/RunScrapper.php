<?php

namespace App\Console\Commands;

use App\Models\ProductUrl;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

use App\Http\Controllers\Api\ProductUrlController;
use App\Http\Controllers\Api\PriceHistoryProductController;

use App\Http\Requests\ProductUrl\UpdateProductUrlRequest;

use App\Traits\ScraperTrait;
use Illuminate\Validation\ValidationException;

class RunScrapper extends Command
{
    use ScraperTrait;

    protected $signature = 'scraper:run';
    protected $description = 'Execute the web scraper';

    protected $productUrlController;
    protected $priceHistoryController;

    public function __construct(ProductUrlController $productUrlController, PriceHistoryProductController $priceHistoryProductController)
    {
        parent::__construct();
        $this->productUrlController = $productUrlController;
        $this->priceHistoryController = $priceHistoryProductController;
        $this->initBrowser();
    }

    private function getStore($url)
    {
        $urlModel = ProductUrl::with('store')
            ->where('url', $url)
            ->first();

        return $urlModel->store;
    }

    private function updateProductUrl($url, $data)
    {
        try {
            $productUrl = ProductUrl::where('url', $url)->firstOrFail();
            $this->productUrlController->update(new UpdateProductUrlRequest($data), $productUrl);
        } catch (QueryException $e) {
            $this->error("Database error scraping {$url}: " . $e->getMessage());
        } catch (\Exception $e) {
            $this->error("General error scraping {$url}: " . $e->getMessage());
        }
    }

    private function handleUrl($url)
    {
        $this->info("Scraping data from {$url['url']}");

        if ($url['status_id'] === 3) {
            $this->error("Error scraping {$url['url']}: URL is denied");
            return;
        }

        $store = $this->getStore($url['url']);
        if ($store['status_id'] === 3) {
            $this->error("Error scraping {$url['url']}: STORE is denied");
            return;
        }

        $data = $this->scrape($url['url'], $store);

        if (!$data['name'] || !$data['price']) {
            $this->error("Error scraping {$url['url']}: Name or price cannot be empty");
            $data = ['status_id' => '3'];
        } else {
            $data['status_id'] = '1';
        }

        $this->updateProductUrl($url['url'], $data);
    }

    public function handle()
    {
        $this->info("Scraper started\n");
        $urls = ProductUrl::orderBy('id', 'desc')->get(['url', 'status_id'])->toArray();

        if (empty($urls)) {
            $this->info("No URLs found.");
            return;
        }

        foreach ($urls as $url) {
            $this->handleUrl($url);
        }

        try {
            $this->priceHistoryController->index();
        } catch (\Exception $e) {
            $this->error("An error ocurred " . $e->getMessage());
        }

        $this->info("\nScraping completed.");
    }
}
