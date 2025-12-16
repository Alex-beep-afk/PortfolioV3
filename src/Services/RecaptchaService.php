<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RecaptchaService
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $secretKey
    ) {
    }

    public function verify(string $recaptchaResponse, string $remoteIp ): bool
    {
        if (empty($recaptchaResponse)) {
            return false;
        }

        try {
            $response = $this->httpClient->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
                'body' => [
                    'secret' => $this->secretKey,
                    'response' => $recaptchaResponse,
                    'remoteip' => $remoteIp,
                ],
            ]);

            $data = $response->toArray();
            return $data['success'] ?? false;
        } catch (\Exception $e) {
            return false;
        }
    }
}