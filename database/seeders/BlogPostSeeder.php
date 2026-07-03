<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\User;
use App\Support\BlogArticles;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        if (BlogPost::query()->exists()) {
            return;
        }

        $author = User::where('role', 'admin')->first();

        foreach (BlogArticles::all() as $article) {
            BlogPost::create([
                'user_id' => $author?->id,
                'title' => $article['title'],
                'slug' => $article['slug'],
                'excerpt' => $article['excerpt'],
                'content' => $article['excerpt'] . "\n\n" . 'Full article content coming soon.',
                'featured_image' => $article['image'],
                'category_slug' => $article['category_slug'],
                'tags' => [],
                'status' => $article['status'],
                'featured' => $article['featured'] ?? false,
                'views' => $article['views'] ?? 0,
                'read_time' => $article['read_time'] ?? 5,
                'author_name' => $article['author_name'] ?? null,
                'author_role' => $article['author_role'] ?? null,
                'published_at' => ($article['status'] ?? '') === 'published'
                    ? ($article['updated_at'] ?? now())
                    : null,
                'created_at' => $article['updated_at'] ?? now(),
                'updated_at' => $article['updated_at'] ?? now(),
            ]);
        }
    }
}
