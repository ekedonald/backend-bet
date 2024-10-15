<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public $basePaymentUrl = "https://api.nowpayments.io";
    public $coingeckoBaseUrl = "https://api.coingecko.com/api/v3/";

    public function getClientAppUrl() {
        return App::environment(['local', 'staging']) ? env('CLIENT_APP_DEV_URL') : env('CLIENT_APP_PROD_URL');
    }

    public function getServerAppUrl() {
        return App::environment(['local', 'staging']) ? env('SERVER_APP_DEV_URL') : env('SERVER_APP_PROD_URL');
    }

    public function initTransaction($amount, $currency) {
        try {
            $response = Http::withToken(env('PAYMENT_LIVE_TOKEN'))
                ->withHeaders([
                    'x-api-key' => env('PAYMENT_LIVE_API_KEY'),
                ])
                ->post($this->basePaymentUrl . '/v1/payment', [
                    "price_amount" => $amount,
                    "price_currency" => "usd",
                    "pay_currency" => $currency,
                    "order_description" => "fund account",
                    "ipn_callback_url" => $this->getServerAppUrl() . 'payment',
                    "is_fixed_rate" => true,
                    "is_fee_paid_by_user" => true,
                ]);
    
            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Payment API error: ' . $response->body());
                return false;
            }
        } catch (Exception $e) {
            Log::error('Payment API exception: ' . $e->getMessage());
            return false;
        }
    }
    

    public function getCoinFromExternalApi($name){
        $response = Http::get($this->coingeckoBaseUrl. 'coins/markets', [
            'vs_currency' => 'usd',
            'ids' => strtolower($name)
        ]);
        return $response;
    }

    public function transactionStatus($paymentId)
    {
        $response = Http::withToken(env('PAYMENT_LIVE_TOKEN'))
            ->withHeaders([
                'x-api-key' => env('PAYMENT_LIVE_API_KEY'),
            ])
            ->get($this->basePaymentUrl . '/v1/payment/' . $paymentId);

        return $response->json();
    }

}
