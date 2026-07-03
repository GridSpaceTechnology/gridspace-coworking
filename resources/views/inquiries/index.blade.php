@extends('layouts.dashboard')

@section('title', 'Messages | GridSpace')

@push('head')
<style>
    .chat-shell {
        box-shadow: 0 4px 24px rgba(28, 44, 64, 0.08);
    }
    .chat-bubble-in {
        background-color: #d2e4ff;
        color: #1c2c40;
        border-radius: 16px 16px 16px 4px;
    }
    .chat-bubble-out {
        background-color: #1c2c40;
        color: #ffffff;
        border-radius: 16px 16px 4px 16px;
    }
    .chat-convo-active {
        background-color: #e8f1fc;
    }
    .chat-scroll::-webkit-scrollbar { width: 6px; }
    .chat-scroll::-webkit-scrollbar-thumb { background: #d8dadc; border-radius: 999px; }
</style>
@endpush

@section('content')
@php
    $pageSubtitle = $isHost
        ? 'Chat with guests about workspace bookings'
        : 'Chat with host about workspace bookings';
    $viewerIsHost = $viewerIsHost ?? ($isHost || $isAdmin);
@endphp

@if($isHost)
    @include('host.partials.subnav')
@endif

<section class="mb-6">
    <h1 class="font-manrope text-3xl md:text-4xl font-bold text-[#1c2c40] mb-1 tracking-tight">Messages</h1>
    <p class="font-inter text-sm text-on-surface-variant">{{ $pageSubtitle }}</p>
</section>

@if(session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-2.5 text-sm text-green-800 font-inter">
        {{ session('success') }}
    </div>
@endif

@if($inquiries->isEmpty())
    <div class="bg-white rounded-2xl chat-shell border border-outline-variant/60 p-12 text-center">
        <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-surface-container flex items-center justify-center">
            <span class="material-symbols-outlined text-4xl text-outline">chat_bubble_outline</span>
        </div>
        <h3 class="font-manrope text-xl font-bold text-[#1c2c40] mb-2">No conversations yet</h3>
        <p class="font-inter text-sm text-on-surface-variant mb-6 max-w-md mx-auto">
            @if($isHost)
                When guests message you about your listings, conversations will appear here.
            @else
                Find a workspace and send an inquiry to start chatting with a host.
            @endif
        </p>
        <a href="{{ route('listings.index') }}" class="inline-flex items-center gap-2 bg-primary-container text-white px-5 py-2.5 rounded-lg font-inter font-semibold text-sm hover:bg-primary transition-colors">
            Find Space
        </a>
    </div>
@else
    <div class="bg-white rounded-2xl chat-shell border border-outline-variant/60 overflow-hidden flex flex-col lg:flex-row min-h-[calc(100vh-280px)] max-h-[720px]">
        {{-- Conversations sidebar --}}
        <aside class="lg:w-[320px] xl:w-[360px] border-b lg:border-b-0 lg:border-r border-outline-variant/60 flex flex-col shrink-0">
            <div class="px-5 py-4 border-b border-outline-variant/40">
                <h2 class="font-manrope text-lg font-bold text-[#1c2c40]">Conversations</h2>
            </div>
            <div class="overflow-y-auto chat-scroll flex-1 max-h-[220px] lg:max-h-none">
                @foreach($inquiries as $inquiry)
                    @php
                        $listing = $inquiry->listing;
                        $host = $listing?->user;
                        $isActive = $selected && $selected->id === $inquiry->id;
                        $unread = $inquiry->unreadCountFor($viewerIsHost);

                        if ($viewerIsHost) {
                            $contactName = $inquiry->name;
                            $contactAvatar = null;
                            $contactInitial = strtoupper(substr($inquiry->name, 0, 1));
                        } else {
                            $contactName = $host?->display_name ?? 'Host';
                            $contactAvatar = $host?->profile_photo_url;
                            $contactInitial = strtoupper(substr($contactName, 0, 1));
                        }

                        $workspaceName = $listing?->name ?? 'Workspace';
                        $listingImage = $listing && $listing->images->first()
                            ? asset('storage/' . $listing->images->first()->image_path)
                            : null;
                    @endphp
                    <a href="{{ route('inquiries.index', ['id' => $inquiry->id]) }}"
                       class="flex items-start gap-3 px-5 py-4 border-b border-outline-variant/30 transition-colors relative {{ $isActive ? 'chat-convo-active' : 'hover:bg-surface-container-low/80' }}">
                        <div class="w-11 h-11 rounded-full overflow-hidden shrink-0 bg-surface-container flex items-center justify-center ring-2 ring-white shadow-sm">
                            @if($contactAvatar)
                                <img src="{{ $contactAvatar }}" alt="" class="w-full h-full object-cover">
                            @elseif($listingImage && ! $viewerIsHost)
                                <img src="{{ $listingImage }}" alt="" class="w-full h-full object-cover">
                            @else
                                <span class="font-manrope font-bold text-sm text-secondary">{{ $contactInitial }}</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0 pr-6">
                            <p class="font-manrope font-bold text-sm text-[#1c2c40] truncate">{{ $contactName }}</p>
                            <p class="font-inter text-xs text-on-surface-variant truncate mb-1">{{ $workspaceName }}</p>
                            <p class="font-inter text-xs text-secondary/80 truncate">{{ Str::limit($inquiry->lastPreview(), 42) }}</p>
                        </div>
                        @if($unread > 0)
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 min-w-[22px] h-[22px] px-1.5 flex items-center justify-center rounded-full bg-primary-container text-white font-mono text-[11px] font-bold leading-none">
                                {{ $unread > 9 ? '9+' : $unread }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>
        </aside>

        {{-- Chat panel --}}
        <div class="flex-1 flex flex-col min-w-0 bg-white">
            @if($selected)
                @php
                    $listing = $selected->listing;
                    $host = $listing?->user;
                    $messages = $selected->threadMessages();

                    if ($viewerIsHost) {
                        $headerName = $selected->name;
                        $headerAvatar = null;
                        $headerInitial = strtoupper(substr($selected->name, 0, 1));
                    } else {
                        $headerName = $host?->display_name ?? 'Host';
                        $headerAvatar = $host?->profile_photo_url;
                        $headerInitial = strtoupper(substr($headerName, 0, 1));
                    }

                    $headerWorkspace = $listing?->name ?? 'Workspace';
                @endphp

                {{-- Chat header --}}
                <div class="px-6 py-4 border-b border-outline-variant/40 flex items-center gap-3 shrink-0">
                    <div class="w-11 h-11 rounded-full overflow-hidden shrink-0 bg-surface-container flex items-center justify-center ring-2 ring-surface-container-low shadow-sm">
                        @if($headerAvatar)
                            <img src="{{ $headerAvatar }}" alt="" class="w-full h-full object-cover">
                        @else
                            <span class="font-manrope font-bold text-sm text-secondary">{{ $headerInitial }}</span>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="font-manrope font-bold text-[#1c2c40] leading-tight">{{ $headerName }}</p>
                        <p class="font-inter text-xs text-on-surface-variant truncate">{{ $headerWorkspace }}</p>
                    </div>
                </div>

                {{-- Messages --}}
                <div id="chat-messages" class="flex-1 overflow-y-auto chat-scroll px-6 py-6 space-y-4 bg-[#fafbfc]">
                    @foreach($messages as $msg)
                        @php
                            $isMine = ($viewerIsHost && $msg->is_host) || (! $viewerIsHost && ! $msg->is_host);
                        @endphp
                        <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[75%] sm:max-w-[65%] px-4 py-3 relative {{ $isMine ? 'chat-bubble-out' : 'chat-bubble-in' }}">
                                <p class="font-inter text-sm leading-relaxed whitespace-pre-wrap pr-10">{{ $msg->body }}</p>
                                <span class="absolute bottom-2 right-3 font-mono text-[10px] {{ $isMine ? 'text-white/70' : 'text-[#1c2c40]/50' }}">
                                    {{ $msg->created_at->format('H:i') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Compose --}}
                <div class="px-6 py-4 border-t border-outline-variant/40 bg-white shrink-0">
                    <form method="POST" action="{{ route('inquiries.messages.store', $selected) }}" class="flex items-center gap-3">
                        @csrf
                        <input
                            type="text"
                            name="message"
                            required
                            maxlength="2000"
                            autocomplete="off"
                            placeholder="Type Message......."
                            class="flex-1 px-4 py-3 border border-outline-variant rounded-lg font-inter text-sm text-on-surface placeholder:text-on-surface-variant/60 focus:ring-2 focus:ring-primary-container/30 focus:border-primary-container outline-none transition-all"
                        >
                        <button type="submit" class="shrink-0 bg-primary-container hover:bg-primary text-white font-manrope font-bold text-sm px-8 py-3 rounded-lg transition-colors shadow-sm">
                            Send
                        </button>
                    </form>
                    @error('message')
                        <p class="text-red-600 text-xs mt-2 font-inter">{{ $message }}</p>
                    @enderror
                </div>
            @else
                <div class="flex-1 flex items-center justify-center bg-[#fafbfc]">
                    <p class="font-inter text-sm text-on-surface-variant">Select a conversation to start chatting</p>
                </div>
            @endif
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
    const chatBox = document.getElementById('chat-messages');
    if (chatBox) {
        chatBox.scrollTop = chatBox.scrollHeight;
    }
</script>
@endpush
