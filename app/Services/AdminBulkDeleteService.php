<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\Booking;
use App\Models\Inquiry;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminBulkDeleteService
{
    public function deleteListings(array $ids): int
    {
        $deleted = 0;

        Listing::whereIn('id', $ids)->with('images')->each(function (Listing $listing) use (&$deleted) {
            foreach ($listing->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }
            $listing->delete();
            $deleted++;
        });

        return $deleted;
    }

    public function deleteUsers(array $ids): int
    {
        $deleted = 0;
        $currentUserId = Auth::id();

        User::whereIn('id', $ids)->each(function (User $user) use (&$deleted, $currentUserId) {
            if ($user->role === 'admin' || $user->id === $currentUserId) {
                return;
            }
            $user->delete();
            $deleted++;
        });

        return $deleted;
    }

    public function deleteBookings(array $ids): int
    {
        return Booking::whereIn('id', $ids)->delete();
    }

    public function deleteBlogPosts(array $ids): int
    {
        $deleted = 0;

        BlogPost::whereIn('id', $ids)->each(function (BlogPost $post) use (&$deleted) {
            if ($post->featured_image && ! str_starts_with($post->featured_image, 'http')) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $post->delete();
            $deleted++;
        });

        return $deleted;
    }

    public function deleteInquiries(array $ids): int
    {
        return Inquiry::whereIn('id', $ids)->delete();
    }
}
