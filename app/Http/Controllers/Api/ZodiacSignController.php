<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ZodiacSign;

class ZodiacSignController extends Controller
{
    public function index()
    {
        $zodiacSigns = ZodiacSign::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $zodiacSigns->map(function ($sign) {
                return [
                    'id' => $sign->id,
                    'name' => $sign->name,
                    'slug' => $sign->slug,
                    'icon' => str_contains($sign->icon, 'frontend/') ? asset($sign->icon) : asset($sign->icon),
                    'sort_order' => $sign->sort_order,
                ];
            })
        ]);
    }
}
