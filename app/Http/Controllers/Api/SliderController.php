<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * Get active sliders
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Slider::active()->ordered();

        if ($request->has('group')) {
            $query->group($request->group);
        }

        $sliders = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $sliders
        ]);
    }
}
