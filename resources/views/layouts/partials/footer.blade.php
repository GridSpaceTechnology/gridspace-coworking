<footer class="bg-[#1c2c40] text-white mt-auto">
    <div class="max-w-container-max mx-auto px-4 md:px-margin-desktop py-12 md:py-16">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
            <div class="sm:col-span-2 lg:col-span-1">
                <a href="{{ route('home') }}" class="flex items-center gap-2 mb-6">
                    <img src="{{ asset('logo.jpeg') }}" alt="GridSpace" class="w-8 h-8 rounded-md object-contain">
                    <span class="font-manrope text-xl font-extrabold">GridSpace</span>
                </a>
                <p class="font-inter text-sm text-white/70 mb-6 max-w-xs leading-relaxed">Connecting professionals with flexible, verified workspaces across Nigeria and beyond.</p>
            </div>
            <div>
                <h4 class="font-manrope font-bold mb-5">Company</h4>
                <ul class="space-y-3 font-inter text-sm text-white/70">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">About Us</a></li>
                    <li><a href="{{ route('home') }}#how-it-works" class="hover:text-white transition-colors">How it Works</a></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-white transition-colors">Blog</a></li>
                    <li><a href="{{ route('invest.index') }}" class="hover:text-white transition-colors">Investors</a></li>
                    <li><a href="{{ route('featured') }}" class="hover:text-white transition-colors">Featured Spaces</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-manrope font-bold mb-5">Support</h4>
                <ul class="space-y-3 font-inter text-sm text-white/70">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Help Center</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Safety</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-manrope font-bold mb-5">Get in Touch</h4>
                <p class="font-inter text-sm text-white/70 mb-4">Want to host, invest, or partner with us?</p>
                <a href="{{ route('invest.index') }}#contact" class="inline-flex items-center gap-2 bg-primary-container text-white font-manrope font-semibold px-5 py-2.5 rounded-lg hover:bg-primary transition-colors text-sm mb-6">
                    Contact Us
                    <span class="material-symbols-outlined text-base">mail</span>
                </a>
                <div class="space-y-2 font-inter text-sm text-white/70">
                    <p class="flex items-center gap-2"><span class="material-symbols-outlined text-sm text-primary-container">mail</span>info@gridspace.com.ng</p>
                    <p class="flex items-center gap-2"><span class="material-symbols-outlined text-sm text-primary-container">call</span>+234 904 657 5527</p>
                </div>
            </div>
        </div>
        <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="font-mono text-xs text-white/50">&copy; {{ date('Y') }} GridSpace. All rights reserved.</p>
            <div class="flex flex-wrap justify-center gap-6">
                <a href="#" class="font-mono text-xs text-white/50 hover:text-white transition-colors">Privacy Policy</a>
                <a href="#" class="font-mono text-xs text-white/50 hover:text-white transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
