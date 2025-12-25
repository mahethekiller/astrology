<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    /**
     * Display a listing of specializations.
     */
    public function index()
    {
        $specializations = Specialization::withCount('astrologerProfiles')->latest()->get();
        return view('admin.specializations.index', compact('specializations'));
    }

    /**
     * Show the form for creating a new specialization.
     */
    public function create()
    {
        return view('admin.specializations.create');
    }

    /**
     * Store a newly created specialization in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:specializations',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Specialization::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.specializations.index')
            ->with('success', 'Specialization created successfully!');
    }

    /**
     * Show the form for editing the specified specialization.
     */
    public function edit(Specialization $specialization)
    {
        $astrologerCount = $specialization->astrologerProfiles()->count();
        return view('admin.specializations.edit', compact('specialization', 'astrologerCount'));
    }

    /**
     * Update the specified specialization in storage.
     */
    public function update(Request $request, Specialization $specialization)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:specializations,name,' . $specialization->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $specialization->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.specializations.index')
            ->with('success', 'Specialization updated successfully!');
    }

    /**
     * Remove the specified specialization from storage.
     */
    public function destroy(Specialization $specialization)
    {
        // Check if specialization is being used by any astrologer
        $astrologerCount = $specialization->astrologerProfiles()->count();

        if ($astrologerCount > 0) {
            return redirect()->route('admin.specializations.index')
                ->with('error', "Cannot delete this specialization. It is currently used by {$astrologerCount} astrologer(s).");
        }

        $specialization->delete();

        return redirect()->route('admin.specializations.index')
            ->with('success', 'Specialization deleted successfully!');
    }
}
