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

    public function getKundli($datetime, $coordinates, $ayanamsa = 1, $advanced = false)
    {
        $endpoint = $advanced ? '/astrology/kundli/advanced' : '/astrology/kundli';
        return $this->makeRequest('GET', $this->baseUrl . $endpoint, [
            'ayanamsa' => $ayanamsa,
            'datetime' => $datetime,
            'coordinates' => $coordinates,
        ]);
    }

    public function getBirthDetails($datetime, $coordinates, $ayanamsa = 1)
    {
        return $this->makeRequest('GET', $this->baseUrl . '/astrology/birth-details', [
            'ayanamsa' => $ayanamsa,
            'datetime' => $datetime,
            'coordinates' => $coordinates,
        ]);
    }

    public function getKaalSarpDosha($datetime, $coordinates, $ayanamsa = 1)
    {
        return $this->makeRequest('GET', $this->baseUrl . '/astrology/kaal-sarp-dosha', [
            'ayanamsa' => $ayanamsa,
            'datetime' => $datetime,
            'coordinates' => $coordinates,
        ]);
    }

    public function getMangalDosha($datetime, $coordinates, $ayanamsa = 1, $advanced = false)
    {
        $endpoint = $advanced ? '/astrology/mangal-dosha/advanced' : '/astrology/mangal-dosha';
        return $this->makeRequest('GET', $this->baseUrl . $endpoint, [
            'ayanamsa' => $ayanamsa,
            'datetime' => $datetime,
            'coordinates' => $coordinates,
        ]);
    }

    public function getSadeSati($datetime, $coordinates, $ayanamsa = 1, $advanced = false)
    {
        $endpoint = $advanced ? '/astrology/sade-sati/advanced' : '/astrology/sade-sati';
        return $this->makeRequest('GET', $this->baseUrl . $endpoint, [
            'ayanamsa' => $ayanamsa,
            'datetime' => $datetime,
            'coordinates' => $coordinates,
        ]);
    }

    public function getChart($datetime, $coordinates, $ayanamsa = 1, $chartType = 'rasi', $chartStyle = 'north-indian', $format = 'svg')
    {
        return $this->makeRequest('GET', $this->baseUrl . '/astrology/chart', [
            'ayanamsa' => $ayanamsa,
            'datetime' => $datetime,
            'coordinates' => $coordinates,
            'chart_type' => $chartType,
            'chart_style' => $chartStyle,
            'format' => $format,
        ], true, false);
    }

    public function getPlanetPosition($datetime, $coordinates, $ayanamsa = 1)
    {
        return $this->makeRequest('GET', $this->baseUrl . '/astrology/planet-position', [
            'ayanamsa' => $ayanamsa,
            'datetime' => $datetime,
            'coordinates' => $coordinates,
        ]);
    }

    public function getPapasamyam($datetime, $coordinates, $ayanamsa = 1)
    {
        return $this->makeRequest('GET', $this->baseUrl . '/astrology/papasamyam', [
            'ayanamsa' => $ayanamsa,
            'datetime' => $datetime,
            'coordinates' => $coordinates,
        ]);
    }

    public function getUpagrahaPosition($datetime, $coordinates, $ayanamsa = 1)
    {
        // Assuming generic planet-position or specific endpoint if exists. 
        // Based on standard prokerala patterns, it's often separate.
        return $this->makeRequest('GET', $this->baseUrl . '/astrology/upagraha-position', [
            'ayanamsa' => $ayanamsa,
            'datetime' => $datetime,
            'coordinates' => $coordinates,
        ]);
    }

    public function getYoga($datetime, $coordinates, $ayanamsa = 1)
    {
        return $this->makeRequest('GET', $this->baseUrl . '/astrology/yoga', [
            'ayanamsa' => $ayanamsa,
            'datetime' => $datetime,
            'coordinates' => $coordinates,
        ]);
    }

    public function getAllCharts($datetime, $coordinates, $ayanamsa = 1, $chartStyle = 'north-indian', $format = 'svg')
    {
        $chartTypes = [
            'rasi',
            'navamsa',
            'lagna',
            'trimsamsa',
            'drekkana',
            'chaturthamsa',
            'dasamsa',
            'ashtamsa',
            'dwadasamsa',
            'shodasamsa',
            'hora',
            'akshavedamsa',
            'shashtyamsa',
            'panchamsa',
            'khavedamsa',
            'saptavimsamsa',
            'shashtamsa',
            'chaturvimsamsa',
            'saptamsa',
            'vimsamsa',
            'upagraha',
            'bhava',
            'sun',
            'moon'
        ];

        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        $responses = Http::pool(function ($pool) use ($chartTypes, $datetime, $coordinates, $ayanamsa, $chartStyle, $format, $token) {
            $requests = [];
            foreach ($chartTypes as $type) {
                $requests[] = $pool->as($type)->withToken($token)->get($this->baseUrl . '/astrology/chart', [
                    'ayanamsa' => $ayanamsa,
                    'datetime' => $datetime,
                    'coordinates' => $coordinates,
                    'chart_type' => $type,
                    'chart_style' => $chartStyle,
                    'format' => $format,
                ]);
            }
            return $requests;
        });

        $results = [];
        foreach ($responses as $type => $response) {
            if ($response instanceof \Illuminate\Http\Client\Response && $response->successful()) {
                $results[$type] = $response->body();
            }
        }

        return ['data' => $results, 'status' => 'ok', 'type' => 'multiple'];
    }

    public function getDashaPeriods($datetime, $coordinates, $ayanamsa = 1, $la = 'en')
    {
        return $this->makeRequest('GET', $this->baseUrl . '/astrology/dasha-periods', [
            'ayanamsa' => $ayanamsa,
            'datetime' => $datetime,
            'coordinates' => $coordinates,
            'la' => $la,
        ]);
    }

    public function getPlanetRelationship($datetime, $coordinates, $ayanamsa = 1, $la = 'en')
    {
        return $this->makeRequest('GET', $this->baseUrl . '/astrology/planet-relationship', [
            'ayanamsa' => $ayanamsa,
            'datetime' => $datetime,
            'coordinates' => $coordinates,
            'la' => $la,
        ]);
    }

    public function getAshtakavarga($datetime, $coordinates, $planet, $ayanamsa = 1, $la = 'en')
    {
        return $this->makeRequest('GET', $this->baseUrl . '/astrology/ashtakavarga', [
            'ayanamsa' => $ayanamsa,
            'datetime' => $datetime,
            'coordinates' => $coordinates,
            'planet' => $planet,
            'la' => $la,
        ]);
    }



    public function getSarvashtakavarga($datetime, $coordinates, $ayanamsa = 1)
    {
        return $this->makeRequest('GET', $this->baseUrl . '/astrology/sarvashtakavarga', [
            'ayanamsa' => $ayanamsa,
            'datetime' => $datetime,
            'coordinates' => $coordinates,
        ]);
    }

    public function getDivisionalPlanetPositions($datetime, $coordinates, $chartType = 'lagna', $ayanamsa = 1, $la = 'en')
    {
        return $this->makeRequest('GET', $this->baseUrl . '/astrology/divisional-planet-position', [
            'ayanamsa' => $ayanamsa,
            'datetime' => $datetime,
            'coordinates' => $coordinates,
            'chart_type' => $chartType,
            'la' => $la,
        ]);
    }

    public function getChandrashtamaPeriods($datetime, $coordinates, $year = null, $rasi = null, $ayanamsa = 1, $la = 'en')
    {
        $params = [
            'ayanamsa' => $ayanamsa,
            'datetime' => $datetime,
            'coordinates' => $coordinates,
            'la' => $la,
        ];

        if ($year) {
            $params['year'] = $year;
        }

        if ($rasi !== null) {
            $params['rasi'] = $rasi;
        }

        return $this->makeRequest('GET', $this->baseUrl . '/astrology/chandrashtama-periods', $params);
    }


    public function searchLocation($query)
    {
        return $this->makeRequest('GET', 'https://client-api.prokerala.com/v1/location/search.json', [
            'q' => $query,
            'limit' => 20,
        ]);
    }

    private function makeRequest($method, $url, $params = [], $retry = true, $expectJson = true)
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return null;
        }

        try {
            $request = Http::withToken($token);

            if ($expectJson) {
                $request->acceptJson();
            }

            $response = $request->$method($url, $params);

            if ($response->successful()) {
                // Log content keying to avoid massive SVG logs if needed, but logging body is fine for now
                // Log::info("Prokerala API Success ($url): " . $response->body());

                if ($expectJson) {
                    return $response->json();
                } else {
                    return ['data' => $response->body(), 'status' => 'ok'];
                }
            }

            // Check for authentication error (Status 401 or specific code in body)
            $is401 = $response->status() === 401;
            $isTokenError = $expectJson && (isset($response->json()['errors'][0]['code']) && $response->json()['errors'][0]['code'] == '643');

            if ($is401 || $isTokenError) {
                Log::warning('Prokerala Token Expired. Refreshing token...');

                if ($retry) {
                    Cache::forget('prokerala_token');
                    return $this->makeRequest($method, $url, $params, false, $expectJson);
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
