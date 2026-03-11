<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Category;
use App\Models\City;
use App\Models\Amenity;
use App\Models\ListingAnalytic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource (public homepage).
     */
    public function index(Request $request)
    {
        $query = Listing::with(['category', 'city', 'images'])
            ->where('status', 'published')
            ->orderBy('featured', 'desc')
            ->orderBy('created_at', 'desc');

        // Apply search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('address', 'like', '%' . $searchTerm . '%');
            });
        }

        // Apply filters
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('city')) {
            $query->whereHas('city', function ($q) use ($request) {
                $q->where('slug', $request->city);
            });
        }

        if ($request->filled('capacity')) {
            $query->where('capacity', '>=', $request->capacity);
        }

        if ($request->filled('price_range')) {
            $query->where('price_range', 'like', '%' . $request->price_range . '%');
        }

        // Handle live search API request
        if ($request->get('live') == '1') {
            $listings = $query->limit(10)->get();
            $formattedListings = $listings->map(function ($listing) {
                return [
                    'id' => $listing->id,
                    'name' => $listing->name,
                    'slug' => $listing->slug,
                    'category_name' => $listing->category->name,
                    'price_range' => $listing->price_range,
                    'image' => $listing->images->first() ? asset('storage/' . $listing->images->first()->image_path) : null
                ];
            });

            return response()->json([
                'listings' => $formattedListings
            ]);
        }

        $listings = $query->paginate(12);
        $categories = Category::all();
        $cities = City::all();

        // Get featured listings (only when no search is active)
        $featuredListings = Listing::with(['category', 'city', 'images'])
            ->where('featured', true)
            ->where('status', 'published')
            ->when(!$request->filled('search') && !$request->filled('category') && !$request->filled('city'), function ($q) {
                return $q->limit(6);
            })
            ->get();

        return view('listings.index', compact('listings', 'categories', 'cities', 'featuredListings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $cities = City::all();
        $amenities = Amenity::all();

        return view('listings.create', compact('categories', 'cities', 'amenities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'nullable|exists:cities,id',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'whatsapp_number' => 'required|string|max:20',
            'website' => 'nullable|url|max:255',
            'price_range' => 'required|string|max:100',
            'capacity' => 'nullable|integer|min:1',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['name']);

        // Set status based on user type
        $user = auth()->user();
        if ($user->isAdmin()) {
            $validated['status'] = 'published'; // Admin listings are auto-published
        } else {
            $validated['status'] = 'pending'; // Regular users need admin approval
        }

        $listing = Listing::create($validated);

        // Handle amenities
        if ($request->has('amenities')) {
            $listing->amenities()->attach($request->amenities);
        }

        // Handle images
        if ($request->has('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('listings', 'public');
                $listing->images()->create([
                    'image_path' => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('dashboard')
            ->with('success', $user->isAdmin()
                ? 'Listing created successfully!'
                : 'Listing submitted for approval! It will be visible once approved by an admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Listing $listing)
    {
        // Track view
        ListingAnalytic::trackView(
            $listing->id,
            request()->ip(),
            request()->userAgent()
        );

        $listing->load(['category', 'city', 'images', 'amenities', 'user']);

        return view('listings.show', compact('listing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing)
    {
        // Check if user owns this listing or is admin
        if (auth()->user()->id !== $listing->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $listing->load(['amenities', 'images']);
        $categories = Category::all();
        $cities = City::all();
        $amenities = Amenity::all();

        return view('listings.edit', compact('listing', 'categories', 'cities', 'amenities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Listing $listing)
    {
        // Check if user owns this listing or is admin
        if (auth()->user()->id !== $listing->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'nullable|exists:cities,id',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'whatsapp_number' => 'required|string|max:20',
            'website' => 'nullable|url|max:255',
            'price_range' => 'required|string|max:100',
            'capacity' => 'nullable|integer|min:1',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validated['name'] !== $listing->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $listing->update($validated);

        // Update amenities
        if ($request->has('amenities')) {
            $listing->amenities()->sync($request->amenities);
        } else {
            $listing->amenities()->detach();
        }

        // Handle new images
        if ($request->has('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('listings', 'public');
                $listing->images()->create([
                    'image_path' => $path,
                    'sort_order' => $listing->images()->count() + $index,
                ]);
            }
        }

        return redirect()->route('dashboard')
            ->with('success', 'Listing updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing)
    {
        // Check if user owns this listing or is admin
        if (auth()->user()->id !== $listing->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        // Delete images
        foreach ($listing->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $listing->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Listing deleted successfully!');
    }

    /**
     * Display the host dashboard.
     */
    public function dashboard()
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // If host is not approved, show approval message
        if ($user->isHost() && !$user->isApproved()) {
            // Still show dashboard but with approval message
        }

        // Get listings based on user type
        if ($user->isAdmin()) {
            // Admin can see all listings
            $listings = Listing::with(['category', 'city', 'images'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            // Regular users and hosts see only their own listings
            $listings = Listing::where('user_id', auth()->id())
                ->with(['category', 'city', 'images'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('dashboard', compact('listings'));
    }
    public function track($listing, $type)
    {
        $listing = Listing::findOrFail($listing);
        $ipAddress = request()->ip();
        $userAgent = request()->userAgent();

        switch ($type) {
            case 'phone':
                ListingAnalytic::trackPhoneClick($listing->id, $ipAddress, $userAgent);
                return redirect()->away('tel:' . $listing->contact_phone);
                break;
            case 'whatsapp':
                ListingAnalytic::trackWhatsAppClick($listing->id, $ipAddress, $userAgent);
                return redirect()->away('https://wa.me/' . preg_replace('/[^0-9]/', '', $listing->whatsapp_number));
                break;
            default:
                abort(404);
        }
    }
}
