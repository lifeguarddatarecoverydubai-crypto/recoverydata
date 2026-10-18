@php
    $sidebarSubject = $subject ?? (isset($service) ? 'Evaluation Request: ' . $service->title : (isset($post) ? 'Evaluation Request from Blog: ' . $post->title : 'Evaluation Request: Cleanroom Diagnostic'));
    $whatsappText = $whatsappMessage ?? (isset($service) ? 'Hello Recovery Data Dubai, I need urgent help with ' . $service->title : (isset($post) ? 'Hello Recovery Data Dubai, I am reading "' . $post->title . '" and need urgent recovery help.' : 'Hello Recovery Data Dubai, I need urgent data recovery assistance.'));
    
    // Resolve services list for the navigator
    if (!isset($navServices)) {
        if (isset($otherServices) && $otherServices->isNotEmpty()) {
            $navServices = $otherServices;
        } elseif (isset($services) && $services->isNotEmpty()) {
            $navServices = isset($service) ? $services->where('id', '!=', $service->id) : $services;
        } else {
            $allServices = \App\Models\Service::published()->ordered()->get();
            $navServices = isset($service) ? $allServices->where('id', '!=', $service->id) : $allServices;
        }
    }
@endphp

<div id="evaluation-box" class="space-y-6">

    <!-- Flash Message Notification -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500 text-white shadow-lg space-y-1">
            <div class="flex items-center gap-2 font-bold text-xs uppercase tracking-wide">
                <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
                <span>Request Received!</span>
            </div>
            <p class="text-xs text-emerald-50 leading-relaxed">{{ session('success') }}</p>
        </div>
    @endif

    <!-- 1. Free Lab Evaluation Form Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-4">
        <div class="flex items-center gap-2 text-xs font-bold uppercase text-brand-600">
            <i data-lucide="clipboard-check" class="w-4 h-4"></i>
            <span>Free Lab Evaluation</span>
        </div>
        <h4 class="text-xl font-black text-slate-900">Request Evaluation</h4>
        <p class="text-xs text-slate-500">Submit your storage device details for an immediate diagnostic assessment under our <strong>No Data, No Charge</strong> policy.</p>

        <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4 pt-1">
            @csrf
            <input type="hidden" name="subject" value="{{ $sidebarSubject }}">

            <!-- Anti-Bot Silent Honeypot -->
            <div style="display: none !important;" aria-hidden="true">
                <input type="text" name="_hp_website" tabindex="-1" autocomplete="off">
                <input type="text" name="bot_check" tabindex="-1" autocomplete="off">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Your Full Name</label>
                <input type="text" name="name" required placeholder="e.g. Ahmed Al Mansoori" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">UAE Phone / WhatsApp</label>
                <input type="tel" name="phone" required placeholder="+971 50 123 4567" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" required placeholder="name@company.ae" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Device Brand / Model</label>
                <input type="text" name="device_model" placeholder="e.g. WD 2TB External, Samsung 980, iPhone 14" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Symptoms / Issue</label>
                <textarea name="message" rows="3" placeholder="Describe symptoms: clicking noise, dropped, not detected in BIOS, water damage, or accidental format..." class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"></textarea>
            </div>

            <div class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-200/80">
                <input type="checkbox" id="pickup_requested" name="pickup_requested" value="yes" class="rounded text-brand-600 focus:ring-brand-500">
                <label for="pickup_requested" class="text-[11px] text-slate-600 font-medium">Request <strong>Free Courier Pickup</strong> across Dubai & UAE</label>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl font-bold text-xs bg-gradient-to-r from-brand-600 to-sky-500 hover:from-brand-500 hover:to-sky-400 text-white shadow-lg shadow-brand-600/30 transition">
                Submit Free Evaluation Request
            </button>
        </form>
    </div>

    <!-- 2. Emergency Direct Hotline Card -->
    <div class="bg-gradient-to-br from-slate-900 to-slate-950 text-white rounded-3xl p-6 border border-slate-800 shadow-md space-y-4">
        <div class="flex items-center gap-2 text-xs font-bold uppercase text-emerald-400">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
            <span>24/7 Emergency Lab</span>
        </div>
        <div class="space-y-1">
            <div class="text-xs text-slate-400">Need immediate help? Call our hotline:</div>
            <a href="tel:+971564101040" class="text-xl font-black text-white hover:text-brand-300 transition block">
                +971 56 410 1040
            </a>
        </div>
        <a href="https://wa.me/971564101040?text={{ urlencode($whatsappText) }}" target="_blank" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center justify-center gap-2 transition shadow-md">
            <i data-lucide="message-circle" class="w-4 h-4"></i>
            <span>Chat on WhatsApp</span>
        </a>
        <div class="pt-2 border-t border-slate-800 text-[11px] text-slate-400 space-y-1.5">
            <div class="flex items-start gap-1.5 text-slate-300">
                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-brand-400 shrink-0 mt-0.5"></i>
                <span>Office # 508, Dusseldorf Business Point, Al Barsha 1, Dubai</span>
            </div>
            <div class="text-slate-400 pl-5">Behind Mall of the Emirates • Mon-Sat 9AM-8PM</div>
        </div>
    </div>

    <!-- 3. Cleanroom Lab Trust Benchmarks Card -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-3.5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
            <div class="flex items-center gap-2">
                <i data-lucide="shield-check" class="w-4 h-4 text-brand-600"></i>
                <h5 class="text-xs font-bold uppercase tracking-wider text-slate-900">Lab Guarantees</h5>
            </div>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Verified</span>
        </div>

        <div class="space-y-2 text-xs">
            <div class="flex items-start gap-2.5 p-2 rounded-xl bg-slate-50 text-slate-700">
                <i data-lucide="check" class="w-3.5 h-3.5 text-brand-600 shrink-0 mt-0.5"></i>
                <div>
                    <strong class="text-slate-900">ISO Class 100 Cleanroom</strong>
                    <div class="text-[11px] text-slate-500">Certified dust-free laboratory environment</div>
                </div>
            </div>
            <div class="flex items-start gap-2.5 p-2 rounded-xl bg-slate-50 text-slate-700">
                <i data-lucide="check" class="w-3.5 h-3.5 text-brand-600 shrink-0 mt-0.5"></i>
                <div>
                    <strong class="text-slate-900">No Data – No Charge</strong>
                    <div class="text-[11px] text-slate-500">Zero recovery fee if data is unrecoverable</div>
                </div>
            </div>
            <div class="flex items-start gap-2.5 p-2 rounded-xl bg-slate-50 text-slate-700">
                <i data-lucide="check" class="w-3.5 h-3.5 text-brand-600 shrink-0 mt-0.5"></i>
                <div>
                    <strong class="text-slate-900">Free UAE Courier Pickup</strong>
                    <div class="text-[11px] text-slate-500">Door-to-door secure transport in all 7 Emirates</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Recovery Services Navigator Card -->
    @if(isset($navServices) && $navServices->count() > 0)
        <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h5 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                    {{ isset($service) ? 'Other Services' : 'Our Recovery Services' }}
                </h5>
                <a href="{{ route('service.index') }}" class="text-[11px] font-bold text-brand-600 hover:underline">View All</a>
            </div>
            <div class="space-y-1.5">
                @foreach($navServices as $other)
                    <a href="{{ route('service.show', $other->slug) }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 transition group">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="{{ $other->icon ?: 'database' }}" class="w-4 h-4 text-brand-600 shrink-0"></i>
                            <span class="text-xs font-bold text-slate-800 group-hover:text-brand-600 transition">{{ $other->title }}</span>
                        </div>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-0.5 transition shrink-0"></i>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</div>
