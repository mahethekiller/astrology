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

        $kundli = $this->prokeralaService->getKundli($datetime, $coordinates, $ayanamsa);

        return view('frontend.kundli.result', compact('kundli', 'request'));
    }
}
