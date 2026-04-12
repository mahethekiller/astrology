<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;

class StatisticsController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_astrologers' => Setting::getValue('stat_total_astrologers', '50000+'),
                'years_of_excellence' => Setting::getValue('stat_years_excellence', '11+'),
                'happy_customers' => Setting::getValue('stat_happy_customers', '500+'),
            ]
        ]);
    }
}
