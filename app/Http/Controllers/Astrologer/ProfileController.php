<?php

namespace App\Http\Controllers\Astrologer;

use App\Http\Controllers\Controller;
use App\Models\AstrologerProfile;
use App\Models\Specialization;
use App\Models\Language;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the astrologer's profile.
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->astrologerProfile;

        if (!$profile) {
            return redirect()->route('astrologer.dashboard')->with('error', 'Profile not found.');
        }

        $specializations = Specialization::active()->orderBy('name')->get();
        $languages = Language::active()->orderBy('name')->get();

        // Get global commission settings as default if not specific to astrologer
        $globalChatCommission = Setting::getValue('global_chat_commission', 20);
        $globalCallCommission = Setting::getValue('global_voice_commission', 20);

        $chatCommission = $profile->chat_commission_percentage ?? $globalChatCommission;
        $callCommission = $profile->call_commission_percentage ?? $globalCallCommission;

        return view('astrologer.profile.edit', compact('profile', 'specializations', 'languages', 'chatCommission', 'callCommission'));
    }

    /**
     * Update the astrologer's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->astrologerProfile;

        if (!$profile) {
            return redirect()->route('astrologer.dashboard')->with('error', 'Profile not found.');
        }

        $request->validate([
            'display_name' => 'required|string|max:150',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:-18 years',
            'experience_years' => 'required|integer|min:0|max:70',
            'about' => 'required|string',
            'chat_price' => 'required|numeric|min:0',
            'call_price' => 'required|numeric|min:0',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'languages' => 'required|array|min:1',
            'languages.*' => 'required|exists:languages,id',
            'specializations' => 'required|array|min:1',
            'specializations.*' => 'required|exists:specializations,id',
        ]);

        $profileData = $request->only([
            'display_name',
            'gender',
            'date_of_birth',
            'experience_years',
            'about',
            'chat_price',
            'call_price'
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            if ($profile->profile_image) {
                $this->deleteImage($profile->profile_image);
            }
            $profileData['profile_image'] = $this->uploadImage($request->file('profile_image'));
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            if ($profile->cover_image) {
                $this->deleteImage($profile->cover_image);
            }
            $profileData['cover_image'] = $this->uploadImage($request->file('cover_image'));
        }

        $profile->update($profileData);

        // Sync specializations and languages
        $profile->specializations()->sync($request->specializations);
        $profile->languages()->sync($request->languages);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Upload image to storage
     */
    private function uploadImage($image)
    {
        $uploadPath = public_path('uploads/astrologers');

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move($uploadPath, $imageName);

        return $imageName;
    }

    /**
     * Delete image from storage
     */
    private function deleteImage($imageName)
    {
        $imagePath = public_path('uploads/astrologers/' . $imageName);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
}
