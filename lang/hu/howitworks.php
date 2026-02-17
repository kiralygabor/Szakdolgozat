@extends('layout')
 
@section('content')
<div class="bg-white text-slate-900 font-sans antialiased">
   
    <!-- Hero Section (Text Left, Image Right) -->
    <section class="relative bg-[#f8fafc] py-20 md:py-32 overflow-hidden border-b border-slate-100">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-indigo-50/40 hidden lg:block -skew-x-12 translate-x-24"></div>
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Text -->
                <div class="max-w-xl">
                    <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-slate-900 leading-[1.1]">
                        Post your first task <br>
                        <span class="text-indigo-600">in minutes</span>
                    </h1>
                    <p class="mt-6 text-lg md:text-xl text-slate-600 leading-relaxed">
                        Whether you need a hand around the house or a specialized professional, Minijobz is the easiest way to get things done.
                    </p>
                    <div class="mt-10">
                        <a href="#" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-10 rounded-full transition-all shadow-lg">
                            Post a task
                        </a>
                    </div>
                </div>
                <!-- Image -->
                <div class="relative">
                    <div class="relative z-10 aspect-[4/3] overflow-hidden rounded-3xl shadow-2xl border-4 border-white bg-slate-200">
                        <img src="https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=1000&q=80" alt="Professional tasker" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -top-6 -right-6 w-32 h-32 bg-indigo-100 rounded-full mix-blend-multiply opacity-60"></div>
                </div>
            </div>
        </div>
    </section>
 
    <!-- SECTION 1 (Image LEFT, Text RIGHT) -->
    <section class="py-20 md:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Image FIRST (Left) -->
                <div class="relative lg:order-1">
                    <div class="relative z-10 overflow-hidden rounded-3xl shadow-2xl border-4 border-white bg-slate-200">
                        <img src="{{ asset('assets/img/post-a-task.png') }}" alt="Describe what you need" class="w-full h-auto object-contain">
                    </div>
                    <div class="absolute -bottom-6 -left-6 w-40 h-40 bg-indigo-50 rounded-full"></div>
                </div>
                <!-- Text SECOND (Right) -->
                <div class="lg:order-2">
                    <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-6">Describe what you need</h2>
                    <p class="text-lg md:text-xl text-slate-600 leading-relaxed mb-8">
                        It’s free to post. Simply tell us what you need done, when and where you need it. You can even upload photos to help Taskers understand the job.
                    </p>
                    <ul class="space-y-4 mb-10">
                        <li class="flex items-center gap-3 text-slate-700 text-lg font-medium"><i data-feather="check" class="text-emerald-500 w-6 h-6"></i> Set a realistic budget</li>
                        <li class="flex items-center gap-3 text-slate-700 text-lg font-medium"><i data-feather="check" class="text-emerald-500 w-6 h-6"></i> Choose a date and time</li>
                    </ul>
                    <a href="#" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-10 rounded-full transition-all shadow-lg">
                        Post a task
                    </a>
                </div>
            </div>
        </div>
    </section>
 
    <!-- SECTION 2 (Text LEFT, Image RIGHT) -->
    <section class="py-20 md:py-32 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Text FIRST (Left) -->
                <div>
                    <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-6">Review offers & profiles</h2>
                    <p class="text-lg md:text-xl text-slate-600 leading-relaxed mb-8">
                        Taskers will make offers to help you. Review their profiles, check their completion rates, and read reviews from previous customers to find the right person.
                    </p>
                    <div class="p-8 bg-white rounded-3xl shadow-sm border border-slate-100 italic text-slate-600 text-lg mb-10">
                        "I found a great plumber within 10 minutes of posting!" — <span class="font-bold text-slate-900">Sarah M.</span>
                    </div>
                    <a href="#" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-10 rounded-full transition-all shadow-lg">
                        Post a task
                    </a>
                </div>
                <!-- Image SECOND (Right) -->
                <div class="relative">
                    <div class="relative z-10 overflow-hidden rounded-3xl shadow-2xl border-4 border-white bg-slate-200">
                        <img src="{{ asset('assets/img/set-a-price.png') }}" alt="Review offers" class="w-full h-auto object-contain">
                    </div>
                    <div class="absolute -top-6 -right-6 w-40 h-40 bg-indigo-100/50 rounded-full"></div>
                </div>
            </div>
        </div>
    </section>
 
    <!-- SECTION 3 (Image LEFT, Text RIGHT) -->
    <section class="py-20 md:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Image FIRST (Left) -->
                <div class="relative lg:order-1">
                    <div class="relative z-10 overflow-hidden rounded-3xl shadow-2xl border-4 border-white bg-slate-200">
                        <img src="assets/img/offers.jpg" alt="Review & Choose" class="w-full h-auto object-contain">
                    </div>
                </div>
                <!-- Text SECOND (Right) -->
                <div class="lg:order-2">
                    <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-6">Review & Choose</h2>
                    <p class="text-lg md:text-xl text-slate-600 leading-relaxed mb-8">
                        Receive quotes from available Taskers. Review their profiles, ratings, and completion rates to find the perfect match for your task.
                    </p>
                    <ul class="space-y-4 mb-10">
                        <li class="flex items-center gap-3 text-slate-700 text-lg font-medium">
                            <i data-feather="user-check" class="text-emerald-500 w-6 h-6"></i>
                            View Tasker profiles and reviews
                        </li>
                        <li class="flex items-center gap-3 text-slate-700 text-lg font-medium">
                            <i data-feather="message-square" class="text-emerald-500 w-6 h-6"></i>
                            Chat directly with Taskers
                        </li>
                    </ul>
                    <a href="#" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-10 rounded-full transition-all shadow-lg">
                        Post a task
                    </a>
                </div>
            </div>
        </div>
    </section>
 
    <!-- SECTION 4 (Text LEFT, Image RIGHT) -->
    <section class="py-20 md:py-32 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Text FIRST (Left) -->
                <div>
                    <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-6">Get it done</h2>
                    <p class="text-lg md:text-xl text-slate-600 leading-relaxed mb-8">
                        When the task is complete, simply release the payment. It's that easy! Your payment is held securely by Minijobz Pay until the job is finished.
                    </p>
                    <div class="flex items-center gap-6 mb-10">
                        <div class="flex -space-x-4">
                            <img class="h-14 w-14 rounded-full ring-4 ring-white" src="https://i.pravatar.cc/100?u=11" alt="">
                            <img class="h-14 w-14 rounded-full ring-4 ring-white" src="https://i.pravatar.cc/100?u=12" alt="">
                            <img class="h-14 w-14 rounded-full ring-4 ring-white" src="https://i.pravatar.cc/100?u=13" alt="">
                        </div>
                        <p class="text-lg font-bold text-slate-500">20,000+ happy users</p>
                    </div>
                    <a href="#" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-10 rounded-full transition-all shadow-lg">
                        Post a task
                    </a>
                </div>
                <!-- Image SECOND (Right) -->
                <div class="relative">
                    <div class="relative z-10 aspect-[4/3] overflow-hidden rounded-3xl shadow-2xl border-4 border-white bg-slate-200">
                        <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=1600&q=80" alt="Get it done" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute inset-0 bg-indigo-600/5 rounded-3xl -rotate-3 -z-10"></div>
                </div>
            </div>
        </div>
    </section>
 
<!-- REDESIGNED Safety Section: We've got you covered -->
    <section class="py-24 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-start">
                <!-- Left: Main Heading -->
                <div>
                    <h2 class="text-5xl md:text-6xl font-black text-[#000033] tracking-tighter leading-none mb-8">
                        We've got you covered
                    </h2>
                    <p class="text-xl md:text-2xl text-[#000033] leading-snug mb-10 max-w-md">
                        Whether you’re a posting a task or completing a task, you can do both with the peace of mind that Minijobz is there to support.
                    </p>
                    <a href="#" class="inline-block bg-blue-50 text-blue-600 font-bold py-4 px-8 rounded-full hover:bg-blue-100 transition-colors">
                        Minijobz's insurance cover
                    </a>
                </div>
 
                <!-- Right: Two Column Features -->
                <div class="grid sm:grid-cols-2 gap-12">
                    <div>
                        <div class="text-[#000033] mb-4">
                            <i data-feather="user" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-bold text-[#000033] mb-4">Public liability insurance</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Minijobz Insurance covers you for any accidental injury to the customer or property damage whilst performing certain task activities.
                        </p>
                    </div>
                    <div>
                        <div class="text-[#000033] mb-4">
                            <i data-feather="star" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-bold text-[#000033] mb-4">Top rated insurance</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Minijobz Insurance is provided by world-class partners, ensuring some of the world’s most reputable, stable and innovative insurance support.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
 
    <!-- NEW Section: Ratings & Reviews -->
    <section class="py-24 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Left: Image with Floating Cards -->
                <div class="relative px-8">
                    <!-- Main Image -->
                    <div class="relative z-10 rounded-[2.5rem] overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1540103359322-30397163bad1?auto=format&fit=crop&w=800&q=80" alt="Tasker" class="w-full h-auto">
                    </div>
 
                    <!-- Floating Rating Card (Top Left) -->
                    <div class="absolute -top-6 -left-2 z-20 bg-[#f0f5ff] p-5 rounded-2xl shadow-xl border border-white min-w-[160px]">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-2xl font-black text-[#000033]">5.0</span>
                            <i data-feather="star" class="w-5 h-5 fill-blue-600 text-blue-600"></i>
                        </div>
                        <p class="text-sm font-bold text-[#000033]">Overall rating</p>
                        <p class="text-xs text-slate-500">110 ratings</p>
                    </div>
 
                    <!-- Floating Review Card (Bottom Right) -->
                    <div class="absolute -bottom-10 -right-2 z-20 bg-[#f0f5ff] p-6 rounded-2xl shadow-xl border border-white max-w-[280px]">
                        <div class="flex items-center gap-3 mb-3">
                            <img src="https://i.pravatar.cc/100?u=tommy" class="w-8 h-8 rounded-full">
                            <p class="font-bold text-[#000033] text-sm">Tommy’s review</p>
                        </div>
                        <p class="text-slate-600 text-sm leading-relaxed italic">
                            Highly recommend. Henry is a highly skilled handyman and took great care within my home.
                        </p>
                    </div>
                </div>
 
                <!-- Right: Text Content -->
                <div class="lg:pl-12">
                    <h2 class="text-5xl md:text-6xl font-black text-[#000033] tracking-tighter leading-none mb-8">
                        Ratings & reviews
                    </h2>
                    <p class="text-lg md:text-xl text-[#000033] leading-relaxed mb-10">
                        Review Tasker's portfolios, skills, badges on their profile, and see their transaction verified ratings, reviews & completion rating (to see their reliability) on tasks they’ve previously completed on Minijobz. This empowers you to make sure you’re choosing the right person for your task.
                    </p>
                    <a href="#" class="inline-block bg-blue-50 text-blue-600 font-bold py-4 px-10 rounded-full hover:bg-blue-100 transition-colors">
                        Get started for free
                    </a>
                </div>
            </div>
        </div>
    </section>
 
    <!-- NEW Section: Communication -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Left: Chat Interface Mockup -->
                <div class="relative flex justify-center lg:justify-start">
                    <div class="w-full max-w-[440px] aspect-square bg-blue-600 rounded-[2.5rem] relative flex flex-col justify-center p-8 gap-4 overflow-visible">
                       
                        <!-- Avatar 1 -->
                        <img src="https://i.pravatar.cc/100?u=henry" class="absolute left-[-20px] top-[40%] w-12 h-12 rounded-full border-4 border-white shadow-lg z-20">
                       
                        <!-- Bubble 1 (White) -->
                        <div class="bg-white rounded-2xl p-5 ml-4 relative z-10 shadow-md">
                            <p class="text-[#000033] font-bold text-lg mb-1">Hi Henry!</p>
                            <p class="text-slate-700 leading-tight">I’ve moved the fridge out ready for when you arrive tomorrow.</p>
                        </div>
 
                        <!-- Bubble 2 (Dark Blue) -->
                        <div class="bg-[#000033] rounded-2xl p-5 mr-4 self-end relative z-10 shadow-md max-w-[85%]">
                            <p class="text-white leading-tight">That’s fantastic. Confirming I’ll be there at 8:30am. See you in the morning!</p>
                        </div>
 
                        <!-- Avatar 2 -->
                        <img src="https://i.pravatar.cc/100?u=tasker1" class="absolute right-[-20px] bottom-[20%] w-12 h-12 rounded-full border-4 border-white shadow-lg z-20">
                    </div>
                </div>
 
                <!-- Right: Text Content -->
                <div class="lg:pl-12">
                    <h2 class="text-5xl md:text-6xl font-black text-[#000033] tracking-tighter leading-none mb-8">
                        Communication
                    </h2>
                    <p class="text-lg md:text-xl text-[#000033] leading-relaxed mb-10">
                        Use Minijobz to stay in contact from the moment your task is posted until it’s completed. Accept an offer and you can privately message the Tasker to discuss final details, and get your task completed.
                    </p>
                    <a href="#" class="inline-block bg-blue-50 text-blue-600 font-bold py-4 px-10 rounded-full hover:bg-blue-100 transition-colors">
                        Get started for free
                    </a>
                </div>
            </div>
        </div>
    </section>
 
    <!-- App Section -->
    <section class="bg-indigo-600 py-24 px-6 overflow-hidden">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-center">
            <div class="text-white">
                <h2 class="text-4xl md:text-6xl font-extrabold mb-8 leading-tight">The Minijobz App</h2>
                <p class="text-indigo-100 text-xl mb-12 leading-relaxed">Notifications, messaging, and task management—all in your pocket.</p>
                <div class="flex flex-wrap gap-4">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" alt="App Store" class="h-16 cursor-pointer">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" class="h-16 cursor-pointer">
                </div>
            </div>
            <div class="flex justify-center">
                <div class="w-80 h-[600px] bg-slate-800 rounded-[3.5rem] border-[12px] border-slate-900 shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 w-full h-8 bg-slate-900"></div>
                    <div class="p-8 space-y-8 mt-10">
                        <div class="h-6 w-3/4 bg-slate-700 rounded-full"></div>
                        <div class="h-48 w-full bg-indigo-500/20 rounded-3xl"></div>
                        <div class="h-4 w-full bg-slate-700 rounded-full"></div>
                        <div class="h-4 w-2/3 bg-slate-700 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
 
</div>
 
<script>
    feather.replace();
</script>
@endsection