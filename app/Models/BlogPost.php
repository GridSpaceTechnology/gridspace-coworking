<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    public const CATEGORIES = [
        'workspace-trends' => 'Workspace Trends',
        'hosting-tips' => 'Hosting Tips',
        'community-stories' => 'Community Stories',
        'future-of-work' => 'Future of Work',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'category_slug',
        'tags',
        'status',
        'featured',
        'views',
        'read_time',
        'author_name',
        'author_role',
        'published_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function getCategoryAttribute(): string
    {
        return self::CATEGORIES[$this->category_slug] ?? ucwords(str_replace('-', ' ', $this->category_slug));
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->featured_image) {
            return null;
        }

        if (str_starts_with($this->featured_image, 'http://') || str_starts_with($this->featured_image, 'https://')) {
            return $this->featured_image;
        }

        return asset('storage/' . $this->featured_image);
    }

    public function getDateAttribute(): string
    {
        $date = $this->published_at ?? $this->created_at;

        return $date?->format('M d, Y') ?? '';
    }

    public function getAuthorDisplayAttribute(): string
    {
        if ($this->attributes['author_name'] ?? null) {
            return $this->attributes['author_name'];
        }

        return $this->user?->display_name ?? 'GridSpace Team';
    }

    public function getAuthorInitialsAttribute(): string
    {
        return strtoupper(substr($this->author_display, 0, 1));
    }

    public static function estimateReadTime(?string $content): int
    {
        $words = str_word_count(strip_tags($content ?? ''));

        return max(1, (int) ceil($words / 200));
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $counter = 1;

        while (static::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }

    public function toPreviewArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'status' => $this->status,
            'category' => $this->category,
            'category_slug' => $this->category_slug,
            'views' => $this->views,
            'image' => $this->image_url,
            'author_name' => $this->author_display,
            'read_time' => $this->read_time,
            'updated_at' => $this->updated_at?->toDateString(),
        ];
    }
}
