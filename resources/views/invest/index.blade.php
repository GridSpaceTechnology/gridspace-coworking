@extends('layouts.gridspace')

@section('title', 'Invest in GridSpace | The Future of Work in Africa')

@push('head')
<style>
    .hero-gradient { background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.6)); }
    .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(8px); }
</style>
@endpush

@section('content')
<header class="relative -mx-4 md:-mx-margin-desktop min-h-[480px] md:min-h-[640px] flex items-center overflow-hidden">
    <div class="absolute inset-0 z-0 bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAHHVJa8nVLAK684dJQZzZnXEwObOni1JWOc74_dxbC1b_nZcWbQEm9_IoH-S3lbRyXJo6rjZ9_bDy75Ue38f5bcWEQp4id1SndBJkFNwSs-6ppG-S87PGcNoLqE3ujvwaWLHYDsBn900zFxB1IBNIOCC7aqClpJO1u07yCa2paWssT2gCH74zwCWVWQp-R39zAq_v8r9cbq-uK0eitW4gj4bvISFYlLKq8I6El93v7MMt-l7-7gKkFtCLYH4ZS7uI7oqU1loP1Vpo')"></div>
    <div class="absolute inset-0 hero-gradient z-10"></div>
    <div class="relative z-20 w-full max-w-container-max mx-auto px-4 md:px-margin-desktop text-center py-20">
        <h1 class="font-manrope text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6 tracking-tight">Invest in the Future of Work</h1>
        <p class="font-inter text-lg md:text-xl text-white/90 max-w-3xl mx-auto mb-10">Join us in revolutionizing flexible work and building the world's largest network of on-demand workspaces across the African continent.</p>
        <a href="#contact" class="inline-flex items-center gap-2 bg-primary-container text-white px-10 py-4 rounded-lg font-manrope font-bold text-lg hover:bg-primary transition-colors group">
            Get in Touch
            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
        </a>
    </div>
</header>

<section class="relative -mt-16 z-30 px-4 md:px-margin-desktop mb-12 md:mb-16">
    <div class="max-w-container-max mx-auto bg-white rounded-xl shadow-xl border border-outline-variant p-6 md:p-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 divide-y md:divide-y-0 md:divide-x divide-outline-variant">
            <div class="flex flex-col items-center text-center p-4">
                <div class="w-12 h-12 bg-primary-fixed flex items-center justify-center rounded-full mb-4"><span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">groups</span></div>
                <span class="font-manrope text-3xl font-bold">50K+</span>
                <span class="font-mono text-xs text-secondary uppercase tracking-wide">Active Users</span>
            </div>
            <div class="flex flex-col items-center text-center p-4">
                <div class="w-12 h-12 bg-secondary-fixed flex items-center justify-center rounded-full mb-4"><span class="material-symbols-outlined text-on-secondary-container" style="font-variation-settings: 'FILL' 1;">location_on</span></div>
                <span class="font-manrope text-3xl font-bold">1200+</span>
                <span class="font-mono text-xs text-secondary uppercase tracking-wide">Locations</span>
            </div>
            <div class="flex flex-col items-center text-center p-4">
                <div class="w-12 h-12 bg-tertiary-fixed flex items-center justify-center rounded-full mb-4"><span class="material-symbols-outlined text-on-tertiary-container" style="font-variation-settings: 'FILL' 1;">star</span></div>
                <span class="font-manrope text-3xl font-bold">4.9/5</span>
                <span class="font-mono text-xs text-secondary uppercase tracking-wide">User Rating</span>
            </div>
        </div>
    </div>
</section>

<section class="py-12 md:py-16 bg-white -mx-4 md:-mx-margin-desktop px-4 md:px-margin-desktop">
    <div class="max-w-container-max mx-auto text-center">
        <h2 class="font-manrope text-2xl md:text-3xl font-bold mb-8">About GridSpace</h2>
        <p class="font-inter text-lg text-secondary max-w-4xl mx-auto mb-16 leading-relaxed">We connect remote workers and businesses to verified, affordable, and well-equipped workspaces across multiple cities in Africa. Our platform bridges the gap between the growing demand for flexible work environments and the supply of quality workspace solutions.</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['icon' => 'public', 'title' => 'Pan-African Reach', 'text' => "Expanding across major African cities to serve the continent's growing remote workforce and digital entrepreneurs."],
                ['icon' => 'verified_user', 'title' => 'Quality Assurance', 'text' => 'Every workspace is verified for reliability, safety, high-speed connectivity, and professional aesthetic standards.'],
                ['icon' => 'diversity_3', 'title' => 'Community Impact', 'text' => 'Empowering freelancers, startups, and local economies to thrive in the global digital market through accessible infrastructure.'],
            ] as $pillar)
            <div class="group p-8 rounded-xl border border-transparent hover:border-outline-variant hover:bg-surface hover:shadow-lg transition-all">
                <div class="w-16 h-16 bg-surface-container flex items-center justify-center rounded-full mx-auto mb-6 group-hover:scale-110 transition-transform"><span class="material-symbols-outlined text-primary-container text-4xl">{{ $pillar['icon'] }}</span></div>
                <h3 class="font-manrope text-xl font-semibold mb-4">{{ $pillar['title'] }}</h3>
                <p class="text-secondary font-inter leading-relaxed">{{ $pillar['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-12 md:py-16 bg-surface -mx-4 md:-mx-margin-desktop px-4 md:px-margin-desktop">
    <div class="max-w-container-max mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
        <div class="relative">
            <div class="rounded-2xl overflow-hidden shadow-2xl relative z-10 border-4 border-white">
                <img class="w-full h-[400px] md:h-[500px] object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCL9Xbk2OGXHwOwhVOEXopPumAXI313qkhyiwrf3-NBND-JirgMcsIWk0P_ansid2zPPjqW9dt-Q2RROiYcATBwI2LFDFFoqSDaRH-bccriTo99bfMcPkVmQ5VV6ezuYiqK55xh5Dg5oXGfLOJQLCbDLxf2zlHm7RGfFoAXHVcLWT3LsiZIxDm9U7rpOfCHi97pFBcXhWthFWda1KPRhekS83BeaRhoZZl_yEeYfKRVmMZkXXsoUf9pMkaIztPGDY62W7UjZmg0cHc" alt="Business partnership">
            </div>
            <div class="absolute -bottom-6 -right-4 md:-bottom-10 md:-right-10 z-20 w-72 md:w-80 p-6 glass-card rounded-xl border border-outline-variant shadow-2xl">
                <div class="flex justify-between items-center mb-4"><span class="font-manrope font-bold">Growing Market</span><span class="material-symbols-outlined text-secondary">trending_up</span></div>
                <div class="h-32 md:h-40 flex items-end justify-between gap-1">
                    @foreach([30, 45, 60, 75, 95] as $i => $h)
                    <div class="w-full bg-primary-container rounded-t" style="height: {{ $h }}%; opacity: {{ 0.2 + $i * 0.2 }}"></div>
                    @endforeach
                </div>
                <p class="mt-4 font-mono text-xs text-secondary uppercase tracking-wide">Projected Growth 2025</p>
            </div>
        </div>
        <div class="space-y-10 lg:pt-8">
            <div>
                <h2 class="font-manrope text-2xl md:text-3xl font-bold mb-6">Why Partner With Us</h2>
                <p class="font-inter text-lg text-secondary">Join us in building the future of work across Africa with proven market opportunity, scalable technology, and transformative impact.</p>
            </div>
            @foreach([
                ['icon' => 'query_stats', 'bg' => 'bg-green-100', 'text' => 'text-green-700', 'title' => 'Growing Market', 'desc' => 'Rising demand for flexible workspaces in Africa, driven by remote work adoption, startup growth, and digital transformation.'],
                ['icon' => 'lan', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'title' => 'Scalable Platform', 'desc' => 'Built to expand quickly across multiple cities with robust technology infrastructure and strong unit economics.'],
                ['icon' => 'rocket_launch', 'bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'title' => 'Impactful Vision', 'desc' => 'Empowering freelancers and enterprises while creating sustainable income opportunities for property owners.'],
            ] as $item)
            <div class="flex gap-6">
                <div class="shrink-0 w-12 h-12 {{ $item['bg'] }} rounded-lg flex items-center justify-center"><span class="material-symbols-outlined {{ $item['text'] }}">{{ $item['icon'] }}</span></div>
                <div>
                    <h4 class="font-manrope font-bold text-lg mb-2">{{ $item['title'] }}</h4>
                    <p class="text-secondary font-inter">{{ $item['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-12 md:py-16 bg-surface-container -mx-4 md:-mx-margin-desktop px-4 md:px-margin-desktop" id="contact">
    <div class="max-w-container-max mx-auto">
        <div class="text-center mb-12">
            <h2 class="font-manrope text-2xl md:text-3xl font-bold mb-4">Let's Build the Future Together</h2>
            <p class="font-inter text-lg text-secondary max-w-2xl mx-auto">Get in touch to explore investment and partnership opportunities.</p>
        </div>
        @if(session('success'))
        <div class="mb-8 max-w-3xl mx-auto rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 flex items-center gap-3"><span class="material-symbols-outlined text-green-600">check_circle</span>{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="mb-8 max-w-3xl mx-auto rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><ul class="list-disc list-inside">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        <div class="flex flex-col lg:flex-row bg-white rounded-3xl overflow-hidden shadow-2xl border border-outline-variant">
            <div class="flex-1 p-8 md:p-16">
                <form method="POST" action="{{ route('invest.store') }}" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label for="name" class="font-mono text-xs uppercase text-on-surface-variant tracking-wide">Full Name</label>
                        <div class="relative"><span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary pointer-events-none">person</span>
                        <input id="name" name="name" value="{{ old('name') }}" required class="w-full pl-10 pr-4 py-4 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary-container outline-none font-inter" placeholder="Enter your full name" type="text"></div>
                    </div>
                    <div class="space-y-2">
                        <label for="email" class="font-mono text-xs uppercase text-on-surface-variant tracking-wide">Email Address</label>
                        <div class="relative"><span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary pointer-events-none">mail</span>
                        <input id="email" name="email" value="{{ old('email') }}" required class="w-full pl-10 pr-4 py-4 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary-container outline-none font-inter" placeholder="Enter your email address" type="email"></div>
                    </div>
                    <div class="space-y-2">
                        <label for="message" class="font-mono text-xs uppercase text-on-surface-variant tracking-wide">Message</label>
                        <textarea id="message" name="message" required rows="4" class="w-full p-4 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary-container outline-none font-inter resize-none" placeholder="Tell us about your interest in investing or partnering...">{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="w-full bg-primary-container text-white font-manrope font-bold py-5 rounded-xl hover:bg-primary shadow-lg transition-all flex items-center justify-center gap-2"><span class="material-symbols-outlined">send</span>Send Message</button>
                </form>
            </div>
            <div class="lg:w-1/3 bg-[#1c2c40] p-8 md:p-16 text-white flex flex-col justify-center">
                <h3 class="font-manrope text-2xl font-semibold mb-10">Get In Touch</h3>
                <div class="space-y-10">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 bg-primary-container rounded flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-white">finance</span></div>
                        <div><h4 class="font-bold text-lg mb-1">Investment Inquiries</h4><a class="text-white/70 hover:text-white font-inter" href="mailto:invest@gridspace.com">invest@gridspace.com</a></div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 bg-primary-container rounded flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-white">handshake</span></div>
                        <div><h4 class="font-bold text-lg mb-1">Partnership Opportunities</h4><a class="text-white/70 hover:text-white font-inter" href="mailto:partnership@gridspace.com">partnership@gridspace.com</a></div>
                    </div>
                </div>
                <div class="mt-16 pt-8 border-t border-white/20 text-sm text-white/60 font-inter">
                    <p>Working hours: 9 AM – 6 PM (GMT+1)</p>
                    <p class="mt-2 flex items-center gap-2"><span class="material-symbols-outlined text-sm">call</span>+234 904 657 5527</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
