<?php

namespace App\Classes;

use App\Models\ProductUrl;
use Illuminate\Database\QueryException;

use App\Http\Controllers\Api\ProductUrlController;
use App\Http\Controllers\Api\PriceHistoryProductController;

use App\Http\Requests\ProductUrl\UpdateProductUrlRequest;
use App\Traits\ScraperTrait;
use Illuminate\Support\Facades\Log;

class ScrapeProduct
{
    use ScraperTrait;

    protected $productUrlController;
    protected $priceHistoryController;

    /**
     * Create a new class instance.
     */
    public function __construct(ProductUrlController $productUrlController, PriceHistoryProductController $priceHistoryProductController)
    {
        $this->priceHistoryController = $priceHistoryProductController;
        $this->productUrlController = $productUrlController;
        $this->initBrowser();
    }

    private function getStore($url)
    {
        $urlModel = ProductUrl::with('store')
            ->where('url', $url)
            ->first();

        return $urlModel->store;
    }

    private function handleUrl($url)
    {
        Log::info("Scraping data from {$url['url']}");

        if ($url['status_id'] === 3) {
            Log::error("Error scraping {$url['url']}: URL is denied");
            return;
        }

        $store = $this->getStore($url['url']);

        if ($store['status_id'] === 3) {
            Log::error("Error scraping {$url['url']}: STORE is denied");
            return;
        }

        $data = $this->scrape($url['url'], $store);

        Log::info("Data from {$url['url']}:\n" . json_encode($data));

        if (!$data['name'] || !$data['price']) {
            Log::error("Error scraping {$url['url']}: Name or price cannot be empty");
            $data = ['status_id' => '3'];
        } else {
            $data['status_id'] = '1';
        }

        $this->updateProductUrl($url['url'], $data);
    }

    private function updateProductUrl($url, $data)
    {
        try {
            $productUrl = ProductUrl::where('url', $url)->firstOrFail();
            $this->productUrlController->update(new UpdateProductUrlRequest($data), $productUrl);
        } catch (QueryException $e) {
        } catch (\Exception $e) {
        }
    }

    public function scrapeProduct($url)
    {
        Log::info("Scraping started.");

        $url = ProductUrl::where('url', $url)->first(['url', 'status_id'])->toArray();
        $this->handleUrl($url);

        try {
            $this->priceHistoryController->index();
        } catch (\Exception $e) {
            Log::error("An error ocurred " . $e->getMessage());
        }

        Log::info("Scraping completed.\n");
    }
}
