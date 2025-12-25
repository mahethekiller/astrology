<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AstrologerProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class AstrologerProfileController extends Controller
{
    /**
     * Display a listing of astrologer profiles.
     */
    public function index()
    {
        $profiles = AstrologerProfile::with('user')->latest()->get();
        return view('admin.astrologer_profiles.index', compact('profiles'));
    }

    /**
     * Show the form for creating a new astrologer profile.
     */
    public function create()
    {
        $specializations = \App\Models\Specialization::active()->orderBy('name')->get();
        $languages = \App\Models\Language::active()->orderBy('name')->get();
        return view('admin.astrologer_profiles.create', compact('specializations', 'languages'));
    }

    /**
     * Store a newly created astrologer profile in storage.
     */
    public function store(Request $request)
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
            'chat_price' => 'nullable|numeric|min:0',
            'call_price' => 'nullable|numeric|min:0',
            'about' => 'required|string',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'languages' => 'required|array|min:1',
            'languages.*' => 'required|exists:languages,id',
            'specializations' => 'required|array|min:1',
            'specializations.*' => 'required|exists:specializations,id',
            'verification_status' => 'required|in:pending,approved,rejected',
            'is_featured' => 'boolean',
            'is_online' => 'boolean',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        DB::beginTransaction();

        try {
            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
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
                'chat_price' => $request->chat_price,
                'call_price' => $request->call_price,
                'about' => $request->about,
                'profile_image' => $profileImageName,
                'cover_image' => $coverImageName,
                'verification_status' => $request->verification_status,
                'is_featured' => $request->has('is_featured'),
                'is_online' => $request->has('is_online'),
                'status' => $request->status,
            ]);

            // Sync specializations and languages
            $profile->specializations()->sync($request->specializations);
            $profile->languages()->sync($request->languages);

            DB::commit();

            return redirect()->route('admin.astrologer-profiles.index')
                ->with('success', 'Astrologer profile created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded images if transaction fails
            if (isset($profileImageName)) {
                $this->deleteImage($profileImageName);
            }
            if (isset($coverImageName)) {
                $this->deleteImage($coverImageName);
            }

            // echo 'Error: ' . $e->getMessage() . '';

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create astrologer profile: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified astrologer profile.
     */
    public function edit(AstrologerProfile $astrologerProfile)
    {
        $astrologerProfile->load(['user', 'specializations', 'languages']);
        $specializations = \App\Models\Specialization::active()->orderBy('name')->get();
        $languages = \App\Models\Language::active()->orderBy('name')->get();
        return view('admin.astrologer_profiles.edit', compact('astrologerProfile', 'specializations', 'languages'));
    }

    /**
     * Update the specified astrologer profile in storage.
     */
    public function update(Request $request, AstrologerProfile $astrologerProfile)
    {
        $request->validate([
            // User account fields
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $astrologerProfile->user_id,
            'phone' => 'nullable|string|max:20',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],

            // Astrologer profile fields
            'display_name' => 'required|string|max:150',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:-18 years',
            'experience_years' => 'required|integer|min:0|max:70',
            'chat_price' => 'nullable|numeric|min:0',
            'call_price' => 'nullable|numeric|min:0',
            'about' => 'required|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'languages' => 'required|array|min:1',
            'languages.*' => 'required|exists:languages,id',
            'specializations' => 'required|array|min:1',
            'specializations.*' => 'required|exists:specializations,id',
            'verification_status' => 'required|in:pending,approved,rejected',
            'is_featured' => 'boolean',
            'is_online' => 'boolean',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        DB::beginTransaction();

        try {
            // Update user account
            $astrologerProfile->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Update password if provided
            if ($request->filled('password')) {
                $astrologerProfile->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            // Handle profile image upload
            $profileImageName = $astrologerProfile->profile_image;
            if ($request->hasFile('profile_image')) {
                $this->deleteImage($astrologerProfile->profile_image);
                $profileImageName = $this->uploadImage($request->file('profile_image'));
            }

            // Handle cover image upload
            $coverImageName = $astrologerProfile->cover_image;
            if ($request->hasFile('cover_image')) {
                if ($astrologerProfile->cover_image) {
                    $this->deleteImage($astrologerProfile->cover_image);
                }
                $coverImageName = $this->uploadImage($request->file('cover_image'));
            }

            // Update astrologer profile
            $astrologerProfile->update([
                'display_name' => $request->display_name,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'experience_years' => $request->experience_years,
                'chat_price' => $request->chat_price,
                'call_price' => $request->call_price,
                'about' => $request->about,
                'profile_image' => $profileImageName,
                'cover_image' => $coverImageName,
                'verification_status' => $request->verification_status,
                'is_featured' => $request->has('is_featured'),
                'is_online' => $request->has('is_online'),
                'status' => $request->status,
            ]);

            // Sync specializations and languages
            $astrologerProfile->specializations()->sync($request->specializations);
            $astrologerProfile->languages()->sync($request->languages);

            DB::commit();

            return redirect()->route('admin.astrologer-profiles.index')
                ->with('success', 'Astrologer profile updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update astrologer profile: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified astrologer profile from storage.
     */
    public function destroy(AstrologerProfile $astrologerProfile)
    {
        DB::beginTransaction();

        try {
            // Delete images
            $this->deleteImage($astrologerProfile->profile_image);
            if ($astrologerProfile->cover_image) {
                $this->deleteImage($astrologerProfile->cover_image);
            }

            // Store user for deletion
            $user = $astrologerProfile->user;

            // Delete profile
            $astrologerProfile->delete();

            // Delete associated user account
            $user->delete();

            DB::commit();

            return redirect()->route('admin.astrologer-profiles.index')
                ->with('success', 'Astrologer profile and user account deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.astrologer-profiles.index')
                ->with('error', 'Failed to delete astrologer profile: ' . $e->getMessage());
        }
    }

    /**
     * Upload image to storage
     */
    private function uploadImage($image)
    {
        $uploadPath = public_path('uploads/astrologers');

        // Create directory if it doesn't exist
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
