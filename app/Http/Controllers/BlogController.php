<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $categories = collect([
            ['slug' => 'all', 'label' => 'All Articles'],
        ])->merge(collect(BlogPost::CATEGORIES)->map(fn ($label, $slug) => [
            'slug' => $slug,
            'label' => $label,
        ]))->values();

        $activeCategory = $request->get('category', 'all');

        $query = BlogPost::published()->with('user')->latest('published_at');

        if ($activeCategory !== 'all') {
            $query->where('category_slug', $activeCategory);
        }

        if ($request->filled('search')) {
            $term = '%' . $request->search . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('excerpt', 'like', $term)
                    ->orWhere('content', 'like', $term);
            });
        }

        $featured = BlogPost::published()->featured()->with('user')->latest('published_at')->first()
            ?? BlogPost::published()->with('user')->latest('published_at')->first();

        $posts = $query->when($featured, fn ($q) => $q->where('id', '!=', $featured->id))
            ->get();

        if ($featured && $activeCategory !== 'all' && $featured->category_slug !== $activeCategory) {
            $featured = null;
        }

        if ($featured && $request->filled('search')) {
            $term = strtolower($request->search);
            $matches = str_contains(strtolower($featured->title), $term)
                || str_contains(strtolower($featured->excerpt ?? ''), $term);
            if (! $matches) {
                $featured = null;
            }
        }

        return view('blog.index', compact('featured', 'posts', 'categories', 'activeCategory'));
    }

    public function show(BlogPost $post): View
    {
        if ($post->status !== 'published') {
            abort(404);
        }

        $post->increment('views');
        $post->load('user');

        $related = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where('category_slug', $post->category_slug)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'related'));
    }
}
