<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AstrologerProfile;
use App\Models\Specialization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AstrologerController extends Controller
{
    public function index(Request $request): View|JsonResponse|Response
    {
        $specializations = Specialization::active()->orderBy('name')->get();

        $query = AstrologerProfile::active()
            ->approved()
            ->with(['specializations', 'languages', 'user']);

        // Filter by Specialization
        if ($request->filled('specialization')) {
            $slug = $request->specialization;
            // If strictly filtering by ID or Slug, we can do whereHas
            $query->whereHas('specializations', function ($q) use ($slug) {
                $q->where('slug', $slug);
            });
        }

        // Filter by Search Term
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('display_name', 'like', "%{$search}%")
                    ->orWhere('about', 'like', "%{$search}%")
                    ->orWhereHas('specializations', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('languages', function ($lq) use ($search) {
                        $lq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $astrologers = $query->paginate(12)->withQueryString();

        if ($request->ajax()) {
            return response(view('frontend.astrologer.partials.astrologer-list', compact('astrologers'))->render());
        }

        return view('frontend.astrologer.index', compact('specializations', 'astrologers'));
    }

    public function show(int $id): View
    {
        return view('frontend.astrologer.show', ['id' => $id]);
    }
}
