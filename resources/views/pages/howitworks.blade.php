@extends('layout')

@section('content')
<div class="bg-white text-slate-900 font-sans antialiased">
    
    <!-- Hero Section: Clean & Focused -->
    <section class="relative bg-[#f6f8fa] py-16 md:py-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
            <div class="z-10">
                <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-slate-900 leading-tight">
                    {!! __('howitworks.hero_title') !!}
                </h1>
                <p class="mt-6 text-lg md:text-xl text-slate-600 leading-relaxed max-w-xl">
                    {{ __('howitworks.hero_desc') }}
                </p>
                <div class="mt-10">
                    <a href="{{ route('post-task') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-10 rounded-full transition-all shadow-lg hover:shadow-indigo-200">
                        {{ __('howitworks.hero_btn') }}
                    </a>
                </div>
            </div>
            <div class="relative">
                <!-- Using a more lifestyle-oriented image placeholder -->
                <img src="https://images.unsplash.com/photo-1581578731548-c64695cc6954?auto=format&fit=crop&w=800&q=80" alt="Person working" class="rounded-2xl shadow-2xl z-10 relative">
                <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-indigo-100 rounded-full -z-0"></div>
            </div>
        </div>
    </section>

    <!-- How it Works: The Z-Pattern Layout -->
    <section class="py-24 px-6">
        <div class="max-w-6xl mx-auto space-y-32">
            
            <!-- Step 1 -->
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div class="order-2 md:order-1">
                    <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80" alt="Post a task" class="rounded-2xl shadow-xl">
                </div>
                <div class="order-1 md:order-2">
                    <span class="text-indigo-600 font-bold uppercase tracking-widest text-sm">{{ __('howitworks.step1.badge') }}</span>
                    <h2 class="text-3xl md:text-4xl font-bold mt-4 mb-6">{{ __('howitworks.step1.title') }}</h2>
                    <p class="text-lg text-slate-600 leading-relaxed">
                        {{ __('howitworks.step1.desc') }}
                    </p>
                    <ul class="mt-6 space-y-3">
                        <li class="flex items-center gap-3 text-slate-700"><i data-feather="check" class="text-emerald-500 w-5"></i> {{ __('howitworks.step1.item1') }}</li>
                        <li class="flex items-center gap-3 text-slate-700"><i data-feather="check" class="text-emerald-500 w-5"></i> {{ __('howitworks.step1.item2') }}</li>
                    </ul>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <span class="text-indigo-600 font-bold uppercase tracking-widest text-sm">{{ __('howitworks.step2.badge') }}</span>
                    <h2 class="text-3xl md:text-4xl font-bold mt-4 mb-6">{{ __('howitworks.step2.title') }}</h2>
                    <p class="text-lg text-slate-600 leading-relaxed">
                        {{ __('howitworks.step2.desc') }}
                    </p>
                    <div class="mt-8 p-6 bg-slate-50 rounded-xl border-l-4 border-indigo-500 italic text-slate-600">
                        {!! __('howitworks.step2.quote') !!}
                    </div>
                </div>
                <div>
                    <img src="https://images.unsplash.com/photo-1552581234-26160f608093?auto=format&fit=crop&w=800&q=80" alt="Pick a tasker" class="rounded-2xl shadow-xl">
                </div>
            </div>

            <!-- Step 3 -->
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div class="order-2 md:order-1">
                    <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=800&q=80" alt="Job done" class="rounded-2xl shadow-xl">
                </div>
                <div class="order-1 md:order-2">
                    <span class="text-indigo-600 font-bold uppercase tracking-widest text-sm">{{ __('howitworks.step3.badge') }}</span>
                    <h2 class="text-3xl md:text-4xl font-bold mt-4 mb-6">{{ __('howitworks.step3.title') }}</h2>
                    <p class="text-lg text-slate-600 leading-relaxed">
                        {{ __('howitworks.step3.desc') }}
                    </p>
                    <div class="mt-8 flex gap-4">
                        <div class="flex -space-x-3">
                            <img class="inline-block h-10 w-10 rounded-full ring-2 ring-white" src="https://i.pravatar.cc/100?u=1" alt="">
                            <img class="inline-block h-10 w-10 rounded-full ring-2 ring-white" src="https://i.pravatar.cc/100?u=2" alt="">
                            <img class="inline-block h-10 w-10 rounded-full ring-2 ring-white" src="https://i.pravatar.cc/100?u=3" alt="">
                        </div>
                        <p class="text-sm text-slate-500 self-center">{{ __('howitworks.step3.users_count') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust & Safety Section: Icon Grid -->
    <section class="bg-slate-900 py-24 text-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-bold">{{ __('howitworks.safety.title') }}</h2>
                <p class="mt-4 text-slate-400 text-lg">{{ __('howitworks.safety.desc') }}</p>
            </div>
            <div class="grid md:grid-cols-3 gap-12 text-center">
                <div>
                    <div class="mx-auto w-16 h-16 bg-indigo-500/20 flex items-center justify-center rounded-full mb-6 text-indigo-400">
                        <i data-feather="shield" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">{{ __('howitworks.safety.insurance_title') }}</h3>
                    <p class="text-slate-400">{{ __('howitworks.safety.insurance_desc') }}</p>
                </div>
                <div>
                    <div class="mx-auto w-16 h-16 bg-indigo-500/20 flex items-center justify-center rounded-full mb-6 text-indigo-400">
                        <i data-feather="lock" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">{{ __('howitworks.safety.payments_title') }}</h3>
                    <p class="text-slate-400">{{ __('howitworks.safety.payments_desc') }}</p>
                </div>
                <div>
                    <div class="mx-auto w-16 h-16 bg-indigo-500/20 flex items-center justify-center rounded-full mb-6 text-indigo-400">
                        <i data-feather="star" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">{{ __('howitworks.safety.reviews_title') }}</h3>
                    <p class="text-slate-400">{{ __('howitworks.safety.reviews_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Earn Money Section: Two-Tone CTA -->
    <section class="py-24 px-6 bg-white text-center">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-4xl font-bold mb-6">{{ __('howitworks.earn.title') }}</h2>
            <p class="text-xl text-slate-600 mb-10 leading-relaxed">
                {{ __('howitworks.earn.desc') }}
            </p>
            <a href="#" class="inline-block border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-600 hover:text-white font-bold py-4 px-10 rounded-full transition-all">
                {{ __('howitworks.earn.btn') }}
            </a>
        </div>
    </section>

    <!-- App Download Section: Modern Mockup Style -->
    <section class="bg-indigo-600 py-20 px-6 overflow-hidden">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
            <div class="text-white">
                <h2 class="text-4xl font-bold mb-6">{{ __('howitworks.app.title') }}</h2>
                <p class="text-indigo-100 text-lg mb-8">{{ __('howitworks.app.desc') }}</p>
                <div class="flex gap-4">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" alt="App Store" class="h-12 cursor-pointer">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" class="h-12 cursor-pointer">
                </div>
            </div>
            <div class="relative flex justify-center">
                <!-- Simple Mobile Placeholder -->
                <div class="w-64 h-[500px] bg-slate-800 rounded-[3rem] border-[8px] border-slate-900 shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 w-full h-6 bg-slate-900"></div>
                    <div class="p-4 space-y-4">
                        <div class="h-4 w-3/4 bg-slate-700 rounded mt-8"></div>
                        <div class="h-32 w-full bg-slate-700 rounded"></div>
                        <div class="h-4 w-full bg-slate-700 rounded"></div>
                        <div class="h-4 w-2/3 bg-slate-700 rounded"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<script>
    // Initialize Feather icons
    feather.replace();
</script>
@endsection