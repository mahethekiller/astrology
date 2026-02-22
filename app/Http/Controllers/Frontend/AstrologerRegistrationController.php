<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AstrologerProfile;
use App\Models\User;
use App\Models\Specialization;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AstrologerRegistrationController extends Controller
{
    /**
     * Show the application form for astrologers.
     */
    public function showRegistrationForm()
    {
        $specializations = Specialization::active()->orderBy('name')->get();
        $languages = Language::active()->orderBy('name')->get();
        return view('frontend.astrologer.register', compact('specializations', 'languages'));
    }

    /**
     * Handle an astrologer registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            // User account fields
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            // Astrologer profile fields
            'display_name' => 'required|string|max:150',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:-18 years',
            'experience_years' => 'required|integer|min:0|max:70',
            'about' => 'required|string',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'languages' => 'required|array|min:1',
            'languages.*' => 'required|exists:languages,id',
            'specializations' => 'required|array|min:1',
            'specializations.*' => 'required|exists:specializations,id',
        ]);

        DB::beginTransaction();

        try {
            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
            ]);

            // Assign astrologer role
            $user->assignRole('astrologer');

            // Handle profile image upload
            $profileImageName = $this->uploadImage($request->file('profile_image'));

            // Handle cover image upload
            $coverImageName = null;
            if ($request->hasFile('cover_image')) {
                $coverImageName = $this->uploadImage($request->file('cover_image'));
            }

            // Create astrologer profile
            $profile = AstrologerProfile::create([
                'user_id' => $user->id,
                'display_name' => $request->display_name,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'experience_years' => $request->experience_years,
                // These are defaults for registration
                'chat_price' => 0,
                'call_price' => 0,
                'chat_commission_percentage' => null,
                'call_commission_percentage' => null,
                'about' => $request->about,
                'profile_image' => $profileImageName,
                'cover_image' => $coverImageName,
                'verification_status' => 'pending',
                'is_featured' => false,
                'is_online' => false,
                'status' => 'inactive',
            ]);

            // Sync specializations and languages
            $profile->specializations()->sync($request->specializations);
            $profile->languages()->sync($request->languages);

            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Your application has been submitted successfully! We will review your profile and get back to you soon.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded images if transaction fails
            if (isset($profileImageName)) {
                $this->deleteImage($profileImageName);
            }
            if (isset($coverImageName)) {
                $this->deleteImage($coverImageName);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit application: ' . $e->getMessage());
        }
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
