<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Category;
use App\Models\City;
use App\Models\Amenity;
use App\Models\BlogPost;
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
        $query = Listing::with(['category', 'city', 'images', 'spaces'])
            ->where('status', 'published');

        // Enhanced featured prioritization algorithm
        if ($request->route()->getName() === 'listings.index' || $request->filled('search') || $request->filled('category') || $request->filled('city') || $request->filled('categories')) {
            $query->orderBy('featured', 'desc')->orderBy('created_at', 'desc');
        } else {
            $query->orderBy('featured', 'desc')
                  ->orderByRaw('CASE WHEN featured = 1 THEN RAND() ELSE RAND() END');
        }

        // Apply search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('address', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('categories')) {
            $slugs = (array) $request->categories;
            $query->where(function ($q) use ($slugs) {
                $q->whereHas('category', fn ($c) => $c->whereIn('slug', $slugs))
                    ->orWhereHas('spaces.category', fn ($c) => $c->whereIn('slug', $slugs));
            });
        } elseif ($request->filled('category')) {
            $slug = $request->category;
            $query->where(function ($q) use ($slug) {
                $q->whereHas('category', fn ($c) => $c->where('slug', $slug))
                    ->orWhereHas('spaces.category', fn ($c) => $c->where('slug', $slug));
            });
        }

        if ($request->filled('city')) {
            $query->whereHas('city', function ($q) use ($request) {
                $q->where('slug', $request->city);
            });
        }

        if ($request->filled('capacity')) {
            $capacity = (int) $request->capacity;
            $query->whereHas('spaces', function ($q) use ($capacity) {
                $q->where('is_active', true)->where('capacity', '>=', $capacity);
            });
        }

        if ($request->filled('min_price')) {
            $minPrice = (float) $request->min_price;
            $query->whereHas('spaces', function ($q) use ($minPrice) {
                $q->where('is_active', true)->where('price', '>=', $minPrice);
            });
        }

        if ($request->filled('max_price')) {
            $maxPrice = (float) $request->max_price;
            $query->whereHas('spaces', function ($q) use ($maxPrice) {
                $q->where('is_active', true)->where('price', '<=', $maxPrice);
            });
        }

        if ($request->filled('price_range')) {
            $query->where('price_range', 'like', '%' . $request->price_range . '%');
        }

        if ($request->filled('amenities')) {
            foreach ((array) $request->amenities as $amenityId) {
                $query->whereHas('spaces.amenities', function ($q) use ($amenityId) {
                    $q->where('amenities.id', $amenityId);
                });
            }
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
        $cities = City::withCount(['listings' => function ($q) {
            $q->where('status', 'published');
        }])->get();

        $featuredLimit = $request->route()->getName() === 'home' ? 4 : 3;

        $featuredListings = Listing::with(['category', 'city', 'images', 'spaces'])
            ->where('status', 'published')
            ->where('featured', true)
            ->inRandomOrder()
            ->limit($featuredLimit)
            ->get();

        if ($featuredListings->isEmpty()) {
            $featuredListings = Listing::with(['category', 'city', 'images', 'spaces'])
                ->where('status', 'published')
                ->latest()
                ->limit($featuredLimit)
                ->get();
        }

        $moreListings = collect();
        $blogPosts = collect();

        if ($request->route()->getName() === 'home') {
            $moreListings = Listing::with(['category', 'city', 'images', 'spaces'])
                ->where('status', 'published')
                ->whereNotIn('id', $featuredListings->pluck('id'))
                ->latest()
                ->limit(6)
                ->get();

            $blogPosts = BlogPost::published()
                ->latest('published_at')
                ->limit(3)
                ->get();
        }

        $hasActiveFilters = $request->filled('search')
            || $request->filled('category')
            || $request->filled('categories')
            || $request->filled('city')
            || $request->filled('capacity')
            || $request->filled('price_range')
            || $request->filled('min_price')
            || $request->filled('max_price')
            || $request->filled('amenities');

        $amenities = Amenity::orderBy('name')->get();

        $viewData = compact(
            'listings',
            'categories',
            'cities',
            'featuredListings',
            'moreListings',
            'blogPosts',
            'hasActiveFilters',
            'amenities'
        );

        if ($request->route()->getName() === 'home' && ! $hasActiveFilters) {
            return view('listings.home', $viewData);
        }

        return view('listings.search', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->check() && auth()->user()->isHost()) {
            return redirect()->route('dashboard', ['add_listing' => 1]);
        }

        $categories = Category::orderBy('name')->get();
        $cities = City::query()
            ->whereNotNull('state')
            ->where('state', '!=', '')
            ->orderBy('state')
            ->orderBy('name')
            ->get();
        $amenities = Amenity::orderBy('name')->get();

        return view('listings.create', compact('categories', 'cities', 'amenities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'whatsapp_number' => 'required|string|max:20',
            'website' => 'nullable|url|max:255',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'spaces' => 'required|array|min:1',
            'spaces.*.name' => 'required|string|max:255',
            'spaces.*.category_id' => 'required|exists:categories,id',
            'spaces.*.price' => 'required|numeric|min:0',
            'spaces.*.price_period' => 'required|in:hour,day,week,month',
            'spaces.*.capacity' => 'required|integer|min:1',
            'spaces.*.description' => 'nullable|string',
            'spaces.*.amenities' => 'nullable|array',
            'spaces.*.amenities.*' => 'exists:amenities,id',
            'spaces.*.images' => 'nullable|array|max:10',
            'spaces.*.images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'spaces.required' => 'Add at least one bookable space to your building.',
            'spaces.min' => 'Add at least one bookable space to your building.',
            'spaces.*.capacity.required' => 'Enter how many people each space can hold.',
            'spaces.*.price_period.in' => 'Price must be per hour, day, week, or month.',
            'images.max' => 'You can upload a maximum of 10 images.',
            'images.*.max' => 'Each image must not exceed 2MB. Please compress or resize your images.',
            'images.*.mimes' => 'Images must be in JPG, PNG, GIF, or WEBP format.',
            'spaces.*.images.max' => 'Each space can have up to 10 photos.',
            'spaces.*.images.*.max' => 'Each space photo must not exceed 2MB.',
        ]);

        $user = auth()->user();
        $firstSpace = $validated['spaces'][0];
        $minPrice = collect($validated['spaces'])->min('price');
        $pricePeriod = $firstSpace['price_period'] ?? 'day';

        $listing = Listing::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'city_id' => $validated['city_id'],
            'description' => $validated['description'],
            'address' => $validated['address'],
            'contact_phone' => $validated['contact_phone'],
            'whatsapp_number' => $validated['whatsapp_number'],
            'website' => $validated['website'] ?? null,
            'category_id' => $firstSpace['category_id'],
            'price' => $minPrice,
            'price_period' => $pricePeriod,
            'price_range' => 'From ₦' . number_format($minPrice, 0) . '/' . $pricePeriod,
            'capacity' => collect($validated['spaces'])->sum('capacity'),
            'status' => $user->isAdmin() ? 'published' : 'pending',
        ]);

        foreach ($validated['spaces'] as $index => $spaceData) {
            $space = $listing->spaces()->create([
                'name' => $spaceData['name'],
                'category_id' => $spaceData['category_id'],
                'description' => $spaceData['description'] ?? null,
                'price' => $spaceData['price'],
                'price_period' => $spaceData['price_period'] ?? 'day',
                'capacity' => $spaceData['capacity'],
                'is_active' => true,
                'sort_order' => $index,
            ]);

            if (! empty($spaceData['amenities'])) {
                $space->amenities()->attach($spaceData['amenities']);
            }

            if ($request->hasFile("spaces.$index.images")) {
                foreach ($request->file("spaces.$index.images") as $imageIndex => $image) {
                    $path = $image->store('listing-spaces', 'public');
                    $space->images()->create([
                        'image_path' => $path,
                        'sort_order' => $imageIndex,
                    ]);
                }
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('listings', 'public');
                $listing->images()->create([
                    'image_path' => $path,
                    'sort_order' => $index,
                    'is_external' => ($index === 0),
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

        $listing->load([
            'category',
            'city',
            'images',
            'amenities',
            'user',
            'spaces.category',
            'spaces.amenities',
            'spaces.images',
        ]);

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

        $listing->load(['amenities', 'images', 'city']);
        $categories = Category::orderBy('name')->get();
        $cities = City::query()
            ->whereNotNull('state')
            ->where('state', '!=', '')
            ->orderBy('state')
            ->orderBy('name')
            ->get();
        $amenities = Amenity::orderBy('name')->get();

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
            'city_id' => 'required|exists:cities,id',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'whatsapp_number' => 'required|string|max:20',
            'website' => 'nullable|url|max:255',
            'price' => 'required|numeric|min:0',
            'price_period' => 'required|in:hour,day,week,month',
            'capacity' => 'nullable|integer|min:1',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'images.max' => 'You can upload a maximum of 10 images.',
            'images.*.max' => 'Each image must not exceed 2MB.',
            'images.*.mimes' => 'Images must be in JPG, PNG, GIF, or WEBP format.',
            'price_period.in' => 'Price period must be per hour, day, week, or month.',
        ]);

        if ($validated['name'] !== $listing->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Build price_range from price and price_period
        $validated['price_range'] = '₦' . number_format($validated['price']) . '/' . $validated['price_period'];

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
