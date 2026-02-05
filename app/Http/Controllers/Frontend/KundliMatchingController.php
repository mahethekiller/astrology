<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\ProkeralaService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KundliMatchingController extends Controller
{
    protected $prokeralaService;

    public function __construct(ProkeralaService $prokeralaService)
    {
        $this->prokeralaService = $prokeralaService;
    }

    public function index()
    {
        return view('frontend.matching.index');
    }

    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'boy_name' => 'required|string|max:255',
            'boy_date' => 'required|date',
            'boy_time' => 'required',
            'boy_latitude' => 'required|numeric',
            'boy_longitude' => 'required|numeric',
            'boy_timezone' => 'required|string',
            'girl_name' => 'required|string|max:255',
            'girl_date' => 'required|date',
            'girl_time' => 'required',
            'girl_latitude' => 'required|numeric',
            'girl_longitude' => 'required|numeric',
            'girl_timezone' => 'required|string',
            'ayanamsa' => 'required|integer|in:1,3,5',
        ]);

        // Format datetime for boy
        $boyDateTime = Carbon::createFromFormat(
            'Y-m-d H:i',
            $validated['boy_date'] . ' ' . $validated['boy_time'],
            $validated['boy_timezone']
        );

        // Format datetime for girl
        $girlDateTime = Carbon::createFromFormat(
            'Y-m-d H:i',
            $validated['girl_date'] . ' ' . $validated['girl_time'],
            $validated['girl_timezone']
        );

        $boyCoordinates = $validated['boy_latitude'] . ',' . $validated['boy_longitude'];
        $girlCoordinates = $validated['girl_latitude'] . ',' . $validated['girl_longitude'];

        $result = $this->prokeralaService->getKundliMatching(
            $boyDateTime->format('c'),
            $boyCoordinates,
            $girlDateTime->format('c'),
            $girlCoordinates,
            $validated['ayanamsa']
        );

        if (!$result) {
            return back()->with('error', 'Unable to calculate Kundli Matching. Please try again.');
        }

        return view('frontend.matching.index', [
            'result' => $result,
            'boyName' => $validated['boy_name'],
            'girlName' => $validated['girl_name'],
            'formData' => $validated
        ]);
    }
}
