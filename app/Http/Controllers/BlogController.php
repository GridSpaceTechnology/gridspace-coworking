<?php

namespace App\Http\Controllers;

use App\Support\BlogArticles;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $allArticles = collect($this->articles())->where('status', 'published');
        $featured = $allArticles->firstWhere('featured', true);
        $posts = $allArticles->where('featured', false)->values();

        $categories = [
            ['slug' => 'all', 'label' => 'All Articles'],
            ['slug' => 'workspace-trends', 'label' => 'Workspace Trends'],
            ['slug' => 'hosting-tips', 'label' => 'Hosting Tips'],
            ['slug' => 'community-stories', 'label' => 'Community Stories'],
            ['slug' => 'future-of-work', 'label' => 'Future of Work'],
        ];

        $activeCategory = $request->get('category', 'all');

        if ($activeCategory !== 'all') {
            $posts = $posts->filter(fn ($article) => $article['category_slug'] === $activeCategory)->values();
            if ($featured && $featured['category_slug'] !== $activeCategory) {
                $featured = null;
            }
        }

        if ($request->filled('search')) {
            $term = strtolower($request->search);
            $matches = fn ($article) => str_contains(strtolower($article['title']), $term)
                || str_contains(strtolower($article['excerpt']), $term);

            $posts = $posts->filter($matches)->values();
            if ($featured && ! $matches($featured)) {
                $featured = null;
            }
        }

        return view('blog.index', compact('featured', 'posts', 'categories', 'activeCategory'));
    }

    private function articles(): array
    {
        return BlogArticles::all();
    }
}
