<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $chatCommission = Setting::getValue('global_chat_commission', 20); // Default 20%
        $callCommission = Setting::getValue('global_voice_commission', 20); // Default 20%

        $statTotalAstrologers = Setting::getValue('stat_total_astrologers', '50000+');
        $statYearsExcellence = Setting::getValue('stat_years_excellence', '11+');
        $statHappyCustomers = Setting::getValue('stat_happy_customers', '500+');

        return view('admin.settings.index', compact(
            'chatCommission',
            'callCommission',
            'statTotalAstrologers',
            'statYearsExcellence',
            'statHappyCustomers'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'global_chat_commission' => 'required|numeric|min:0|max:100',
            'global_voice_commission' => 'required|numeric|min:0|max:100',
            'stat_total_astrologers' => 'nullable|string|max:50',
            'stat_years_excellence' => 'nullable|string|max:50',
            'stat_happy_customers' => 'nullable|string|max:50',
        ]);

        Setting::setValue('global_chat_commission', $request->global_chat_commission);
        Setting::setValue('global_voice_commission', $request->global_voice_commission);

        if ($request->has('stat_total_astrologers')) {
            Setting::setValue('stat_total_astrologers', $request->stat_total_astrologers);
        }
        if ($request->has('stat_years_excellence')) {
            Setting::setValue('stat_years_excellence', $request->stat_years_excellence);
        }
        if ($request->has('stat_happy_customers')) {
            Setting::setValue('stat_happy_customers', $request->stat_happy_customers);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
