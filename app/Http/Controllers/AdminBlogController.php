<?php

namespace App\Http\Controllers;

use App\Support\BlogArticles;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminBlogController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (! Auth::user()?->isAdmin()) {
                abort(403);
            }

            return $next($request);
        });
    }

    public function index(): View
    {
        $posts = collect(BlogArticles::all());

        $stats = [
            'total' => $posts->count(),
            'published' => $posts->where('status', 'published')->count(),
            'total_views' => $posts->sum('views'),
        ];

        return view('admin.blog.index', compact('posts', 'stats'));
    }

    public function create(): View
    {
        $categories = BlogArticles::categories();

        return view('admin.blog.create', compact('categories'));
    }
}
