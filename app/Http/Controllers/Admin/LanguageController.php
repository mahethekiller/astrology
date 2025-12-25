<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * Display a listing of languages.
     */
    public function index()
    {
        $languages = Language::withCount('astrologerProfiles')->latest()->get();
        return view('admin.languages.index', compact('languages'));
    }

    /**
     * Show the form for creating a new language.
     */
    public function create()
    {
        return view('admin.languages.create');
    }

    /**
     * Store a newly created language in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:languages',
            'code' => 'required|string|max:10|unique:languages',
            'is_active' => 'boolean',
        ]);

        Language::create([
            'name' => $request->name,
            'code' => strtolower($request->code),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language created successfully!');
    }

    /**
     * Show the form for editing the specified language.
     */
    public function edit(Language $language)
    {
        $astrologerCount = $language->astrologerProfiles()->count();
        return view('admin.languages.edit', compact('language', 'astrologerCount'));
    }

    /**
     * Update the specified language in storage.
     */
    public function update(Request $request, Language $language)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:languages,name,' . $language->id,
            'code' => 'required|string|max:10|unique:languages,code,' . $language->id,
            'is_active' => 'boolean',
        ]);

        $language->update([
            'name' => $request->name,
            'code' => strtolower($request->code),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language updated successfully!');
    }

    /**
     * Remove the specified language from storage.
     */
    public function destroy(Language $language)
    {
        // Check if language is being used by any astrologer
        $astrologerCount = $language->astrologerProfiles()->count();

        if ($astrologerCount > 0) {
            return redirect()->route('admin.languages.index')
                ->with('error', "Cannot delete this language. It is currently used by {$astrologerCount} astrologer(s).");
        }

        $language->delete();

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language deleted successfully!');
    }
}
