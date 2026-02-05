<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\ProkeralaService;
use Illuminate\Http\Request;

class HoroscopeController extends Controller
{
    protected $prokeralaService;

    public function __construct(ProkeralaService $prokeralaService)
    {
        $this->prokeralaService = $prokeralaService;
    }

    public function daily($sign = null)
    {
        $signs = [
            'aries' => 'Aries',
            'taurus' => 'Taurus',
            'gemini' => 'Gemini',
            'cancer' => 'Cancer',
            'leo' => 'Leo',
            'virgo' => 'Virgo',
            'libra' => 'Libra',
            'scorpio' => 'Scorpio',
            'sagittarius' => 'Sagittarius',
            'capricorn' => 'Capricorn',
            'aquarius' => 'Aquarius',
            'pisces' => 'Pisces'
        ];

        $prediction = null;
        if ($sign) {
            if (!array_key_exists(strtolower($sign), $signs)) {
                abort(404);
            }

            // Get advanced horoscope data for the specific sign with all prediction types
            $prediction = $this->prokeralaService->getAdvancedDailyHoroscope($sign, now(), 'all');
        }

        return view('frontend.horoscope.daily', compact('signs', 'sign', 'prediction'));
    }

}
