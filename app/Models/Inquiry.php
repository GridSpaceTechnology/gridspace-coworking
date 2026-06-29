<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'name',
        'email',
        'phone',
        'message',
        'contacted',
        'guest_last_read_at',
        'host_last_read_at',
        'newsletter_opt_in',
        'ip_address',
    ];

    protected $casts = [
        'newsletter_opt_in' => 'boolean',
        'contacted' => 'boolean',
        'guest_last_read_at' => 'datetime',
        'host_last_read_at' => 'datetime',
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at');
    }

    public function threadMessages(): Collection
    {
        $messages = $this->chatMessages;

        if ($messages->isEmpty()) {
            return collect([
                (object) [
                    'id' => 'seed-' . $this->id,
                    'body' => $this->message,
                    'is_host' => false,
                    'created_at' => $this->created_at,
                ],
            ]);
        }

        $hasInitial = $messages->contains(
            fn ($m) => ! $m->is_host && trim($m->body) === trim($this->message)
        );

        if (! $hasInitial && filled($this->message)) {
            return collect([
                (object) [
                    'id' => 'seed-' . $this->id,
                    'body' => $this->message,
                    'is_host' => false,
                    'created_at' => $this->created_at,
                ],
            ])->concat($messages);
        }

        return $messages;
    }

    public function unreadCountFor(bool $viewerIsHost): int
    {
        $lastRead = $viewerIsHost ? $this->host_last_read_at : $this->guest_last_read_at;

        $query = $this->chatMessages()->where('is_host', ! $viewerIsHost);

        if ($lastRead) {
            $query->where('created_at', '>', $lastRead);
        }

        $count = $query->count();

        if ($count === 0 && $viewerIsHost && ! $lastRead) {
            return 1;
        }

        if ($count === 0 && ! $viewerIsHost && $this->chatMessages()->where('is_host', true)->exists()) {
            $firstHostReply = $this->chatMessages()->where('is_host', true)->oldest()->first();
            if ($firstHostReply && (! $lastRead || $firstHostReply->created_at > $lastRead)) {
                return $this->chatMessages()->where('is_host', true)
                    ->when($lastRead, fn ($q) => $q->where('created_at', '>', $lastRead))
                    ->count();
            }
        }

        return $count;
    }

    public function markReadFor(bool $viewerIsHost): void
    {
        $field = $viewerIsHost ? 'host_last_read_at' : 'guest_last_read_at';
        $this->update([$field => now()]);
    }

    public function lastPreview(): string
    {
        $last = $this->chatMessages()->latest()->first();

        return $last?->body ?? $this->message;
    }
}
