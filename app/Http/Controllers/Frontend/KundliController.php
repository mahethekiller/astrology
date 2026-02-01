<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\ProkeralaService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KundliController extends Controller
{
    protected $prokeralaService;

    public function __construct(ProkeralaService $prokeralaService)
    {
        $this->prokeralaService = $prokeralaService;
    }

    public function index()
    {
        return view('frontend.kundli.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'timezone' => 'required', // Relaxed to allow string or numeric
            'ayanamsa' => 'nullable|integer|in:1,3,5',
            'result_type' => 'nullable|string|in:basic,advanced',
        ]);

        $coordinates = $request->latitude . ',' . $request->longitude;
        $ayanamsa = $request->ayanamsa ?? 1;

        try {
            $tz = new \DateTimeZone($request->timezone);
        } catch (\Exception $e) {
            $tz = new \DateTimeZone('Asia/Kolkata');
        }
        $datetime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time, $tz)->toIso8601String();

        $advanced = $request->input('result_type') === 'advanced';

        if ($advanced) {
            $params = $request->all();
            return redirect()->route('kundli.detailed', $params);
        }

        $kundli = $this->prokeralaService->getKundli($datetime, $coordinates, $ayanamsa, false);

        return view('frontend.kundli.result', compact('kundli', 'request'));
    }

    public function show(Request $request)
    {
        // If coming from search form (POST), redirect to GET with query params
        if ($request->isMethod('post')) {
            $params = $request->only(['date', 'time', 'latitude', 'longitude', 'timezone', 'ayanamsa', 'name', 'gender']);
            $resultType = $request->input('result_type', 'basic');

            if ($resultType === 'advanced') {
                return redirect()->route('kundli.detailed', $params);
            }
            // For basic, we keep using the old generation logical flow or redirect to a basic result page.
            // But existing code uses a POST to 'generate'. We should ideally unify or keep 'generate' for Basic.
            // Let's keep 'generate' for Basic as is in the original code, but if 'advanced' is selected, we use this new flow.
            // Wait, the previous step modified 'generate'. Let's modify 'generate' to redirect if advanced.
        }

        // This method handles the GET request for the detailed view
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'timezone' => 'required',
            'ayanamsa' => 'nullable|integer',
        ]);

        $activeTab = $request->get('tab', 'birth-details');

        $coordinates = $request->latitude . ',' . $request->longitude;
        $ayanamsa = $request->ayanamsa ?? 1;
        try {
            $tz = new \DateTimeZone($request->timezone);
        } catch (\Exception $e) {
            $tz = new \DateTimeZone('Asia/Kolkata');
        }
        $datetime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time, $tz)->toIso8601String();

        $apiData = null;

        switch ($activeTab) {
            case 'kundli':
                $apiData = $this->prokeralaService->getKundli($datetime, $coordinates, $ayanamsa, false);
                break;
            case 'kundli-advanced':
                $apiData = $this->prokeralaService->getKundli($datetime, $coordinates, $ayanamsa, true);
                break;
            case 'birth-details':
                $apiData = $this->prokeralaService->getBirthDetails($datetime, $coordinates, $ayanamsa);
                break;
            case 'mangal-dosha':
                $apiData = $this->prokeralaService->getMangalDosha($datetime, $coordinates, $ayanamsa, false);
                break;
            case 'mangal-dosha-advanced':
                $apiData = $this->prokeralaService->getMangalDosha($datetime, $coordinates, $ayanamsa, true);
                break;
            case 'kaal-sarp-dosha':
                $apiData = $this->prokeralaService->getKaalSarpDosha($datetime, $coordinates, $ayanamsa);
                break;
            case 'sade-sati':
                $apiData = $this->prokeralaService->getSadeSati($datetime, $coordinates, $ayanamsa, false);
                break;
            case 'sade-sati-advanced':
                $apiData = $this->prokeralaService->getSadeSati($datetime, $coordinates, $ayanamsa, true);
                break;
            case 'chart':
                $chartType = $request->get('chart_type', 'rasi');
                $chartStyle = $request->get('chart_style', 'north-indian');

                if ($chartType === 'all') {
                    $apiData = $this->prokeralaService->getAllCharts($datetime, $coordinates, $ayanamsa, $chartStyle, 'svg');
                } else {
                    $apiData = $this->prokeralaService->getChart($datetime, $coordinates, $ayanamsa, $chartType, $chartStyle, 'svg');
                }
                break;
            case 'planet-position':
                $apiData = $this->prokeralaService->getPlanetPosition($datetime, $coordinates, $ayanamsa);
                break;
            case 'yoga':
                $apiData = $this->prokeralaService->getYoga($datetime, $coordinates, $ayanamsa);
                break;
            case 'dasha-periods':
                $apiData = $this->prokeralaService->getDashaPeriods($datetime, $coordinates, $ayanamsa);
                break;
            case 'planet-relationship':
                $apiData = $this->prokeralaService->getPlanetRelationship($datetime, $coordinates, $ayanamsa);
                break;
            case 'ashtakavarga':
                // Get planet from request or default to sun
                $planetName = strtolower($request->query('planet', 'sun'));
                $planetMap = [
                    'sun' => 0,
                    'moon' => 1,
                    'mercury' => 2,
                    'venus' => 3,
                    'mars' => 4,
                    'jupiter' => 5,
                    'saturn' => 6,
                ];
                $planetId = $planetMap[$planetName] ?? 0; // Default to Sun (0)

                $apiData = $this->prokeralaService->getAshtakavarga($datetime, $coordinates, $planetId, $ayanamsa);
                // Also pass the selected planet back to view (can be done via request merge or apiData)
                $request->merge(['selected_planet' => $planetName]);
                break;
            case 'sarvashtakavarga':
                $apiData = $this->prokeralaService->getSarvashtakavarga($datetime, $coordinates, $ayanamsa);
                break;
            case 'divisional-planet-position':
                $chartType = $request->query('chart_type', 'lagna');
                if ($chartType === 'all') {
                    $chartType = 'lagna';
                }
                $apiData = $this->prokeralaService->getDivisionalPlanetPositions($datetime, $coordinates, $chartType, $ayanamsa);
                $request->merge(['selected_chart_type' => $chartType]);
                break;
            case 'upagraha-position':
                $apiData = $this->prokeralaService->getUpagrahaPosition($datetime, $coordinates, $ayanamsa);
                break;
            case 'papasamyam':
                $apiData = $this->prokeralaService->getPapasamyam($datetime, $coordinates, $ayanamsa);
                break;
            case 'chandrashtama-periods':
                // Extract year from datetime or default to current year
                $year = date('Y', strtotime($datetime));
                $apiData = $this->prokeralaService->getChandrashtamaPeriods($datetime, $coordinates, $year, null, $ayanamsa);
                break;
            // Add other cases as needed...
            default:
                // Default to birth details if unknown tab
                $apiData = $this->prokeralaService->getBirthDetails($datetime, $coordinates, $ayanamsa);
                break;
        }

        return view('frontend.kundli.advanced_result', compact('apiData', 'activeTab', 'request'));
    }
}
