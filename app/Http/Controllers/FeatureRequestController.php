<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FeatureRequest;
use App\Models\Listing;

class FeatureRequestController extends Controller
{
    /**
     * Show the feature request form.
     */
    public function create($listingId)
    {
        // Find listing by ID
        $listing = Listing::findOrFail($listingId);

        // Check if user owns listing
        if ($listing->user_id !== Auth::id()) {
            abort(403, 'You can only request to feature your own listings.');
        }

        // Check if there's already a pending request for this listing
        $existingRequest = FeatureRequest::where('listing_id', $listing->id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'You already have a pending feature request for this listing.');
        }

        return view('feature-requests.create', compact('listing'));
    }

    /**
     * Store a new feature request.
     */
    public function store(Request $request, $listingId)
    {
        // Find listing by ID
        $listing = Listing::findOrFail($listingId);

        // Check if user owns the listing
        if ($listing->user_id !== Auth::id()) {
            abort(403, 'You can only request to feature your own listings.');
        }

        // Check if there's already a pending request for this listing
        $existingRequest = FeatureRequest::where('listing_id', $listing->id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'You already have a pending feature request for this listing.');
        }

        $request->validate([
            'request_message' => 'nullable|string|max:1000',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Handle file upload
        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('payment_proofs', $filename, 'public');
            $paymentProofPath = $path;
        }

        // Create feature request
        FeatureRequest::create([
            'user_id' => Auth::id(),
            'listing_id' => $listing->id,
            'status' => 'pending',
            'request_message' => $request->request_message,
            'payment_proof' => $paymentProofPath,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Feature request submitted successfully! Your request is now pending admin approval.');
    }

    /**
     * Show all feature requests for admin.
     */
    public function index()
    {
        $featureRequests = FeatureRequest::with(['user', 'listing', 'listing.images'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('feature-requests.index', compact('featureRequests'));
    }

    /**
     * Approve a feature request.
     */
    public function approve(FeatureRequest $featureRequest)
    {
        $featureRequest->approve('Request approved by admin');

        return redirect()->back()->with('success', 'Feature request approved and listing is now featured!');
    }

    /**
     * Reject a feature request.
     */
    public function reject(Request $request, FeatureRequest $featureRequest)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $featureRequest->reject($request->rejection_reason);

        return redirect()->back()->with('success', 'Feature request rejected.');
    }
}
