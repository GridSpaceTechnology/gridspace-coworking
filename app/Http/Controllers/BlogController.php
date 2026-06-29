<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $allArticles = collect($this->articles());
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
        return [
            [
                'featured' => true,
                'slug' => 'rise-of-regional-hubs',
                'title' => 'The Rise of Regional Hubs: How Distributed Teams are Redefining Productivity',
                'excerpt' => 'As major tech giants shift towards flexible models, regional workspace hubs are emerging as the new centers of innovation. Explore why local connectivity is becoming more important than central headquarters.',
                'category' => 'WORKSPACE TRENDS',
                'category_slug' => 'workspace-trends',
                'read_time' => 12,
                'date' => 'May 18, 2024',
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuC7BZMvaIc81CPJ7uEg3hJql8gg6QZISMc0znoER0Pu9TKyIUaD35UMjDtTKSpgw8-lIfD3Tcvj6LRUvEN-DVed6wv867VYF26P7T8qX3zsRV2xPdiiN2hYSgrxPr_ryLpckZ8LPLwv_zNdMJ3km_TvDZuZhcF13n3mEDzJjeGgV3WgS4fUCd0_XTuytzA7RCNIm6Rlji9qyOLKiQdklPyvXJgtIjc1EQHM77f6kx4-79eT-gMAYTKL0lbSXtEtl5g_QsTcVSPSWrw',
                'author_name' => 'James Danjuma',
                'author_role' => 'Chief Strategy Officer',
                'author_initials' => 'JD',
            ],
            [
                'slug' => 'increase-booking-revenue',
                'title' => '5 Ways to Increase Your Booking Revenue as a Host',
                'excerpt' => 'From lighting upgrades to high-speed fiber internet, learn which amenities actually drive long-term bookings for your workspace.',
                'category' => 'HOSTING TIPS',
                'category_slug' => 'hosting-tips',
                'read_time' => 5,
                'date' => 'MAY 15, 2024',
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCttr6Mbat9xVadAlh5dbcyMoqK-7ZDJEENWMYJqSKeR1tuuEk987aIcUazosva8zTaU6j2l7IkQbGHEmVzF0aa5OsxBd9uKk0wk1jjkOUuL7oci51xHFUCzIz3_-hyb4TQzw1ERDzUiCytT_nBk6J0U11HMxfPNuVSuYfeLNU0MYtIlVeJxk7imNf7aynWb6F4I_XGPbCEUj42n_5eDsWrPofP3kUH8hAxT5GavVkM90r5HbjovD5zgrjStVuyILPFf3PZNGBZezs',
            ],
            [
                'slug' => 'technova-member-spotlight',
                'title' => 'Member Spotlight: How TechNova Scaled via GridSpace',
                'excerpt' => 'A deep dive into how a small startup utilized on-demand meeting rooms and satellite offices to grow their team across three cities without long-term leases.',
                'category' => 'COMMUNITY STORIES',
                'category_slug' => 'community-stories',
                'read_time' => 8,
                'date' => 'MAY 12, 2024',
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBTgW5KiXYpd8tCYpaWvkGl_XpB0-Ub3vkozQv1ko6q4Gy1Z-chKevEY1XJSUO5RsiuJGuM1fwxKEoLwR22wog-wlVFPPNGa-Mtw3a0b6hL1Rjw0Q7xjfJUEfNd2kgFgvwvaHjsBqMYHgRm9WBLuFcyNaYQ7rCAuCBENhu2qlVhyKK5rwRzBK8c89GWn7YsApOlIVp0G8WyC5lUYEV9ijWH2eEAH0HjUpEhkIajLhVHQ_rZkfBEwsLBQXh0JyPqnlJiFcBSGQ9Vi4U',
            ],
            [
                'slug' => '2025-hybrid-work-report',
                'title' => 'Beyond the Office: The 2025 Hybrid Work Report',
                'excerpt' => "Our latest research on where people are working and why 'Third Spaces' are becoming the preferred choice for focus-oriented tasks.",
                'category' => 'FUTURE OF WORK',
                'category_slug' => 'future-of-work',
                'read_time' => 6,
                'date' => 'MAY 08, 2024',
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDZ86jLzzsfyLOcR5I576v9kqLtZTJhiUSFIi5GK1uX4vKoYDxUyOLx5KC6UkiTjnlyBkml-vG0aCpxeK1y0dEWbZQl-3kzPI6S_5VI2bWc-jAQzLBnGvafrPe45VuyMkjlBMmM5PnOcXg4cHQcYxdPMA0cPmam_UXvDqv5NvzFsOQVrPXsibH8nlBdDNStMhlqkTf6pmmCp8N9vYoKVTEcxSf4hdE_n3H0H2C_iF1FMW993wpbhJkQPd1Q5E8xUEIFxlKGU3hZu98',
            ],
            [
                'slug' => 'mastering-deep-work',
                'title' => 'Mastering Deep Work in Shared Spaces',
                'excerpt' => 'Practical strategies for maintaining high levels of focus and concentration while working in dynamic co-working environments.',
                'category' => 'PRODUCTIVITY',
                'category_slug' => 'workspace-trends',
                'read_time' => 4,
                'date' => 'MAY 05, 2024',
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuApeBi6lDiMAEKbEx97t8Q13Tnm8W2t9NbphrrvBqYL6jesB7j1rANuFZwRn6zazoYayQz03yzh2g5dwoQVMNqpgW2tQt3_9dgWc2KD5uIdyFjY2czBKXXKN2iKjdOrQcN_G7FRM4s092y4Y1Xvq1aW4i2Wuf-6f9Sp1N7135uRhR7WQx30tkSGaPCBZ0h7xXG6zaYmy41ipuTN5NAdmPCleiuMYBInwLBDvDVme9e0qkYikkaim_c3ZP4cxWrUVOvIYxyuz0G_c1E',
            ],
            [
                'slug' => 'lighting-workspace-mood',
                'title' => 'How Lighting Affects Workspace Mood and Booking Rates',
                'excerpt' => 'Understanding the psychology of light in commercial spaces and how hosts can optimize their environments for productivity.',
                'category' => 'DESIGN',
                'category_slug' => 'hosting-tips',
                'read_time' => 7,
                'date' => 'APR 28, 2024',
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCxM_6nVk0U7YRKRn5nMEgTogi0BbtNdz9hsb0HSmkH95JMJW2gyJQg22_QGkJ1o5k_9UC3kXz-nz6tC0HFnYkF4B0D42uSG75zSz2r0-PPyYqYrCgWEZ3osT6yQPTGuIbF-QMVJIt_EeO1XqiN6fbAofFv6tq_i9LGg0RKlVqCeQ8E38G1EE3O2iVRIv3FFLYAk-Jv83pddJBzn7oipKAw5PpTwN2wV2H7VusRAAqfx3xe7Vfb7LUs6Ho0p3XWhFPGbBboDYXXACU',
            ],
            [
                'slug' => 'shared-culture-virtual-first',
                'title' => 'Building a Shared Culture in a Virtual-First World',
                'excerpt' => 'Experts share their advice on maintaining team cohesion when employees are spread across multiple workspaces and timezones.',
                'category' => 'CULTURE',
                'category_slug' => 'community-stories',
                'read_time' => 10,
                'date' => 'APR 22, 2024',
                'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDluH2tj5B4_qfnFuA8k2S3nQF0ntKXIt13LEp0JkjYmiryO58-iwxumCgZtAKNq_FKpSmXz0nbBE5V2SHJu0Nd3L90vELWb_dV_dHCHrrMBmPs0GBjzbexwZ2dpkZps7Z3Wc-IPr2apMJMhsDwBtyI5eKK-hldzf-tqOGNSN3rmB70BTh-9JZdRduW_PeciitKEM_toeEEwHAxJ_7YZdJ2uMXTQ0jlv6jduttgFfXpcb4dCrtk8xl5w2hWL-6LRqtOb-eTvDCg2fA',
            ],
        ];
    }
}
