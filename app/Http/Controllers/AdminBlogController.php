<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogPostRequest;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        $posts = BlogPost::with('user')->latest('updated_at')->get();

        $stats = [
            'total' => $posts->count(),
            'published' => $posts->where('status', 'published')->count(),
            'total_views' => $posts->sum('views'),
        ];

        return view('admin.blog.index', compact('posts', 'stats'));
    }

    public function create(): View
    {
        $post = new BlogPost(['status' => 'draft']);
        $categories = BlogPost::CATEGORIES;

        return view('admin.blog.create', compact('post', 'categories'));
    }

    public function store(BlogPostRequest $request): RedirectResponse
    {
        $post = $this->persistPost(new BlogPost(), $request);

        return redirect()
            ->route('admin.blog.edit', $post)
            ->with('success', $post->status === 'published' ? 'Post published successfully.' : 'Draft saved successfully.');
    }

    public function edit(BlogPost $post): View
    {
        $categories = BlogPost::CATEGORIES;

        return view('admin.blog.edit', compact('post', 'categories'));
    }

    public function update(BlogPostRequest $request, BlogPost $post): RedirectResponse
    {
        $this->persistPost($post, $request);

        return redirect()
            ->route('admin.blog.edit', $post)
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(BlogPost $post): RedirectResponse
    {
        $this->deleteFeaturedImage($post);
        $post->delete();

        return redirect()
            ->route('admin.blog.index')
            ->with('success', 'Post deleted successfully.');
    }

    private function persistPost(BlogPost $post, BlogPostRequest $request): BlogPost
    {
        $data = $request->validated();

        $post->fill([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'slug' => $data['slug'] ?: BlogPost::generateUniqueSlug($data['title'], $post->exists ? $post->id : null),
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'] ?? null,
            'category_slug' => $data['category_slug'],
            'tags' => $this->parseTags($data['tags'] ?? null),
            'status' => $data['status'],
            'author_name' => $data['author_name'] ?? null,
            'author_role' => $data['author_role'] ?? null,
            'read_time' => BlogPost::estimateReadTime($data['content'] ?? ''),
        ]);

        if ($data['status'] === 'published' && ! $post->published_at) {
            $post->published_at = now();
        }

        if ($data['status'] === 'draft') {
            $post->published_at = null;
        }

        $featured = $request->boolean('featured');
        if ($featured) {
            BlogPost::where('id', '!=', $post->id ?? 0)->update(['featured' => false]);
        }
        $post->featured = $featured;

        if ($request->hasFile('featured_image')) {
            $this->deleteFeaturedImage($post);
            $post->featured_image = $request->file('featured_image')->store('blog', 'public');
        } elseif ($request->boolean('remove_featured_image')) {
            $this->deleteFeaturedImage($post);
            $post->featured_image = null;
        }

        $post->save();

        return $post;
    }

    private function parseTags(?string $tags): array
    {
        if (! $tags) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $tags))));
    }

    private function deleteFeaturedImage(BlogPost $post): void
    {
        if ($post->featured_image && ! str_starts_with($post->featured_image, 'http')) {
            Storage::disk('public')->delete($post->featured_image);
        }
    }
}
