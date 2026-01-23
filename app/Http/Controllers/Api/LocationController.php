<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProkeralaService;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    protected $prokeralaService;

    public function __construct(ProkeralaService $prokeralaService)
    {
        $this->prokeralaService = $prokeralaService;
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $locations = $this->prokeralaService->searchLocation($request->q);

        return response()->json($locations);
    }
}
