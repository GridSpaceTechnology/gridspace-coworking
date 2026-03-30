<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'listing_id',
        'status',
        'request_message',
        'payment_proof',
        'approved_at',
        'rejected_at',
        'admin_notes',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function approve($adminNotes = null)
    {
        $this->status = 'approved';
        $this->approved_at = now();
        $this->admin_notes = $adminNotes;
        $this->save();

        // Make the listing featured
        $this->listing->featured = true;
        $this->listing->featured_request_status = 'approved';
        $this->listing->save();
    }

    public function reject($adminNotes = null)
    {
        $this->status = 'rejected';
        $this->rejected_at = now();
        $this->admin_notes = $adminNotes;
        $this->save();

        // Update listing status
        $this->listing->featured_request_status = 'rejected';
        $this->listing->save();
    }
}
