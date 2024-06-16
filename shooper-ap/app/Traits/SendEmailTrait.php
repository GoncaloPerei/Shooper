<?php

namespace App\Traits;

use App\Mail\PriceAlertMail;
use Illuminate\Support\Facades\Mail;

trait SendEmailTrait
{
    public function sendPriceAlert($userMail, $priceAlert, $minPrice)
    {
        Mail::to($userMail)->send(new PriceAlertMail($priceAlert, $minPrice));
    }
}
