<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZarinpalService
{
    protected string $merchantId;
    protected bool $sandbox;

    protected string $requestUrl;
    protected string $verifyUrl;
    protected string $startPayUrl;

    public function __construct()
    {
        $this->merchantId = config('zarinpal.merchant_id');
        $this->sandbox = config('zarinpal.sandbox');

        $base = $this->sandbox
            ? 'https://sandbox.zarinpal.com'
            : 'https://payment.zarinpal.com';

        $this->requestUrl = $base . '/pg/v4/payment/request.json';
        $this->verifyUrl = $base . '/pg/v4/payment/verify.json';
        $this->startPayUrl = $base . '/pg/StartPay/';
    }

    public function request(int $amountToman, string $description, string $callbackUrl): array
    {
        $response = Http::post($this->requestUrl, [
            'merchant_id'  => $this->merchantId,
            'amount'       => $amountToman,
            'description'  => $description,
            'callback_url' => $callbackUrl,
        ]);

        $data = $response->json();

        if (isset($data['data']['code']) && $data['data']['code'] === 100) {
            return [
                'success'   => true,
                'authority' => $data['data']['authority'],
                'pay_url'   => $this->startPayUrl . $data['data']['authority'],
            ];
        }

        return [
            'success' => false,
            'message' => $data['errors']['message'] ?? 'خطا در اتصال به درگاه پرداخت',
        ];
    }

    public function verify(string $authority, int $amountToman): array
    {
        $response = Http::post($this->verifyUrl, [
            'merchant_id' => $this->merchantId,
            'amount'      => $amountToman,
            'authority'   => $authority,
        ]);

        $data = $response->json();

        if (isset($data['data']['code']) && in_array($data['data']['code'], [100, 101])) {
            return [
                'success'    => true,
                'ref_id'     => $data['data']['ref_id'] ?? null,
                'card_pan'   => $data['data']['card_pan'] ?? null,
            ];
        }

        return [
            'success' => false,
            'message' => $data['errors']['message'] ?? 'پرداخت تایید نشد',
        ];
    }
}
