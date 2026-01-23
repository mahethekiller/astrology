<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProkeralaService
{
    protected $clientId;
    protected $clientSecret;
    protected $baseUrl = 'https://api.prokerala.com/v2';

    public function __construct()
    {
        $this->clientId = config('services.prokerala.client_id');
        $this->clientSecret = config('services.prokerala.client_secret');
    }

    public function getAccessToken()
    {
        return Cache::remember('prokerala_token', 7200, function () {
            try {
                $response = Http::asForm()->post('https://api.prokerala.com/token', [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ]);

                if ($response->successful()) {
                    return $response->json()['access_token'];
                }

                Log::error('Prokerala Token Error: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('Prokerala Token Exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    public function getDailyHoroscope($sign, $datetime)
    {
        return $this->makeRequest('GET', $this->baseUrl . '/horoscope/daily', [
            'sign' => strtolower($sign),
            'datetime' => $datetime->format('c'),
        ]);
    }

    public function getKundli($datetime, $coordinates, $ayanamsa = 1)
    {
        return $this->makeRequest('GET', $this->baseUrl . '/astrology/kundli', [
            'ayanamsa' => $ayanamsa,
            'datetime' => $datetime,
            'coordinates' => $coordinates,
        ]);
    }

    public function searchLocation($query)
    {
        return $this->makeRequest('GET', 'https://client-api.prokerala.com/v1/location/search.json', [
            'q' => $query,
            'limit' => 20,
        ]);
    }

    private function makeRequest($method, $url, $params = [], $retry = true)
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return null;
        }

        try {
            $response = Http::withToken($token)
                        ->acceptJson()
                ->$method($url, $params);

            if ($response->successful()) {
                Log::info("Prokerala API Success ($url): " . $response->body());
                return $response->json();
            }

            // Check for authentication error (Status 401 or specific code in body)
            if ($response->status() === 401 || (isset($response->json()['errors'][0]['code']) && $response->json()['errors'][0]['code'] == '643')) {
                Log::warning('Prokerala Token Expired. Refreshing token...');

                if ($retry) {
                    Cache::forget('prokerala_token');
                    return $this->makeRequest($method, $url, $params, false);
                }
            }

            Log::error("Prokerala API Error ($url): " . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error("Prokerala API Exception ($url): " . $e->getMessage());
            return null;
        }
    }
}
