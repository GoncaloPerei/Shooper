<?php

namespace App\Traits;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

use Throwable;

trait ScraperTrait
{
    protected $browser;

    public function initBrowser()
    {
        $this->browser = new HttpBrowser(HttpClient::create());
    }

    public function checkScratched($scratched, $normal)
    {
        if ($scratched === $normal) {
            return null;
        }
        return $scratched;
    }

    public function scrape($url, $store)
    {
        $crawler = $this->browser->request('GET', $url);

        $data = [
            'name' => $this->getTitle($crawler, $store->title_tag),
            'price' => $this->getPrice($crawler, $store->price_tag),
            'scratched_price' => $this->getScratchedPrice($crawler, $store->scratched_price_tag),
        ];

        $data['scratched_price'] = $this->checkScratched($data['scratched_price'], $data['price']);

        return $data;
    }

    protected function getTitle($crawler, $titleTag)
    {
        try {
            return $crawler->filter($titleTag)->text();
        } catch (Throwable) {
            return null;
        }
    }

    protected function getPrice($crawler, $priceTag)
    {
        try {
            $data = $crawler->filter($priceTag)->text();
            return $this->cleanPrice($data);
        } catch (Throwable) {
            return null;
        }
    }

    protected function getScratchedPrice($crawler, $scratchedTag)
    {
        try {
            $data = $crawler->filter($scratchedTag)->text();
            return $this->cleanPrice($data);
        } catch (Throwable) {
            return null;
        }
    }

    protected function cleanPrice($price)
    {
        $price = strtok($price, ' ');
        $price = preg_replace('/[^0-9,]/', '', $price);
        $price = str_replace(',', '.', $price);
        return $price;
    }
}
