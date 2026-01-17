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

        return view('admin.settings.index', compact('chatCommission', 'callCommission'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'global_chat_commission' => 'required|numeric|min:0|max:100',
            'global_voice_commission' => 'required|numeric|min:0|max:100',
        ]);

        Setting::setValue('global_chat_commission', $request->global_chat_commission);
        Setting::setValue('global_voice_commission', $request->global_voice_commission);

        return redirect()->back()->with('success', 'Commission settings updated successfully.');
    }
}
