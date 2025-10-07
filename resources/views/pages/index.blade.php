<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskHive - Get Things Done</title>
    <link rel="icon" type="image/x-icon" href="/static/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            500: '#6366f1',
                        },
                        secondary: {
                            500: '#f43f5e',
                        }
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 px-4 py-3 shadow-sm">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-4">
            <div class="flex items-center space-x-2">
                <i data-feather="zap" class="text-secondary-500"></i>
                <span class="text-xl font-bold text-gray-900">TaskHive</span>
            </div>
            <div class="hidden md:flex items-center space-x-6">
                <a href="#" class="text-gray-600 hover:text-secondary-500">Browse Tasks</a>
                <a href="#" class="text-gray-600 hover:text-secondary-500">How It Works</a>
                <a href="#" class="text-gray-600 hover:text-secondary-500">About</a>
                <button class="px-4 py-2 rounded-lg bg-secondary-500 hover:bg-secondary-600 text-white">
                    Post a Task
                </button>
            </div>
            <div class="flex items-center space-x-4">
                <button class="px-4 py-2 rounded-lg bg-primary-500 hover:bg-primary-600 text-white">
                    Sign In
                </button>
                <button class="px-4 py-2 rounded-lg border border-primary-500 text-primary-500 hover:bg-primary-500/10">
                    Sign Up
                </button>
                <button id="theme-toggle" class="p-2 rounded-full hover:bg-gray-200">
                    <i data-feather="sun"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-b from-gray-100 to-gray-200 py-20 px-4">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                    Post or find tasks <span class="text-secondary-500">in minutes</span>
                </h1>
                <p class="text-lg text-gray-600">
                    Connect with people in your neighborhood to get things done. From simple errands to specialized tasks, TaskHive makes it easy.
                </p>
                <div class="flex flex-wrap gap-4">
                    <button class="px-6 py-3 rounded-lg bg-secondary-500 hover:bg-secondary-600 text-white font-medium">
                        Post a Task
                    </button>
                    <button class="px-6 py-3 rounded-lg border border-white hover:bg-gray-800 text-white font-medium">
                        Browse Tasks
                    </button>
                </div>
            </div>
            <div class="relative">
                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-200">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-primary-500 flex items-center justify-center">
                            <i data-feather="user" class="text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-medium">John D.</h3>
                            <p class="text-sm text-gray-400">Posted 15 min ago</p>
                        </div>
                    </div>
                    <h4 class="font-bold text-lg mb-2">Need help assembling IKEA furniture</h4>
                    <p class="text-gray-600 mb-4">I have 2 bookshelves and a desk that need assembly. Will provide all tools needed.</p>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <i data-feather="map-pin" class="text-secondary-500 w-4"></i>
                            <span class="text-sm">Downtown (1.2mi)</span>
                        </div>
                        <span class="font-bold">$60</span>
                    </div>
                </div>
                <div class="absolute -bottom-6 -right-6 bg-white rounded-xl p-6 shadow-lg border border-gray-200 w-3/4 z-10">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-secondary-500 flex items-center justify-center">
                            <i data-feather="user" class="text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-medium">Sarah M.</h3>
                            <p class="text-sm text-gray-400">Posted 1hr ago</p>
                        </div>
                    </div>
                    <h4 class="font-bold text-lg mb-2">Dog walking - 30 min daily</h4>
                    <p class="text-gray-600 mb-4">Need someone to walk my golden retriever Mon-Fri at 2pm.</p>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <i data-feather="map-pin" class="text-secondary-500 w-4"></i>
                            <span class="text-sm">Westside (0.8mi)</span>
                        </div>
                        <span class="font-bold">$20/day</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-16 px-4 bg-white">
  <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
    <!-- Left side -->
    <div>
      <h2 class="text-5xl font-extrabold text-gray-900 mb-6 leading-tight">
        Post your first<br>task in seconds
      </h2>
      <p class="text-lg text-gray-700 mb-8">
        Save yourself hours and get your to-do list completed
      </p>

      <div class="space-y-4 mb-8">
        <div class="flex items-start">
          <div class="w-6 h-6 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full font-semibold mr-3">1</div>
          <p class="text-gray-700">Describe what you need done</p>
        </div>
        <div class="flex items-start">
          <div class="w-6 h-6 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full font-semibold mr-3">2</div>
          <p class="text-gray-700">Set your budget</p>
        </div>
        <div class="flex items-start">
          <div class="w-6 h-6 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full font-semibold mr-3">3</div>
          <p class="text-gray-700">Receive quotes and pick the best Tasker</p>
        </div>
      </div>

      <button class="bg-blue-600 text-white font-semibold py-3 px-6 rounded-full shadow hover:bg-blue-700 transition">
        Post your task
      </button>
    </div>

    <!-- Right side -->
    <div class="bg-blue-50 p-6 rounded-2xl">
      <img src="https://via.placeholder.com/500x400.png?text=Task+Categories+Placeholder" alt="Task categories placeholder" class="rounded-xl shadow-sm w-full">
    </div>
  </div>
</section>


    <!-- Popular Tasks -->
    <section class="py-16 px-4">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12">Popular Tasks</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Task Card 1 -->
                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-secondary-500 transition shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Moving Help</span>
                        <span class="font-bold">$120</span>
                    </div>
                    <h3 class="font-bold text-xl mb-2">Help moving furniture</h3>
                    <p class="text-gray-600 mb-4">Need help moving a couch and dining table to a new apartment 2 blocks away.</p>
                    <div class="flex items-center space-x-2 text-sm text-gray-400">
                        <i data-feather="map-pin" class="w-4"></i>
                        <span>East Village (0.5mi)</span>
                    </div>
                </div>

                <!-- Task Card 2 -->
                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-secondary-500 transition shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Cleaning</span>
                        <span class="font-bold">$75</span>
                    </div>
                    <h3 class="font-bold text-xl mb-2">Apartment deep cleaning</h3>
                    <p class="text-gray-600 mb-4">1 bedroom apartment needs deep cleaning before move-in.</p>
                    <div class="flex items-center space-x-2 text-sm text-gray-400">
                        <i data-feather="map-pin" class="w-4"></i>
                        <span>Midtown (1.7mi)</span>
                    </div>
                </div>

                <!-- Task Card 3 -->
                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-secondary-500 transition shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Delivery</span>
                        <span class="font-bold">$35</span>
                    </div>
                    <h3 class="font-bold text-xl mb-2">Pick up groceries</h3>
                    <p class="text-gray-600 mb-4">Need someone to pick up my grocery order from Whole Foods.</p>
                    <div class="flex items-center space-x-2 text-sm text-gray-400">
                        <i data-feather="map-pin" class="w-4"></i>
                        <span>Uptown (3.2mi)</span>
                    </div>
                </div>

                <!-- Task Card 4 -->
                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-secondary-500 transition shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Tech Help</span>
                        <span class="font-bold">$50</span>
                    </div>
                    <h3 class="font-bold text-xl mb-2">Set up home network</h3>
                    <p class="text-gray-600 mb-4">Need help setting up a mesh WiFi system in my house.</p>
                    <div class="flex items-center space-x-2 text-sm text-gray-400">
                        <i data-feather="map-pin" class="w-4"></i>
                        <span>Riverside (2.1mi)</span>
                    </div>
                </div>

                <!-- Task Card 5 -->
                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-secondary-500 transition shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Handyman</span>
                        <span class="font-bold">$90</span>
                    </div>
                    <h3 class="font-bold text-xl mb-2">Fix leaky faucet</h3>
                    <p class="text-gray-600 mb-4">Kitchen faucet is leaking, needs repair or replacement.</p>
                    <div class="flex items-center space-x-2 text-sm text-gray-400">
                        <i data-feather="map-pin" class="w-4"></i>
                        <span>Downtown (0.8mi)</span>
                    </div>
                </div>

                <!-- Task Card 6 -->
                <div class="bg-white rounded-xl p-6 border border-gray-200 hover:border-secondary-500 transition shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Tutoring</span>
                        <span class="font-bold">$40/hr</span>
                    </div>
                    <h3 class="font-bold text-xl mb-2">Math tutor for 8th grader</h3>
                    <p class="text-gray-600 mb-4">Need help with algebra twice a week after school.</p>
                    <div class="flex items-center space-x-2 text-sm text-gray-400">
                        <i data-feather="map-pin" class="w-4"></i>
                        <span>Southside (4.3mi)</span>
                    </div>
                </div>
            </div>
            <div class="text-center mt-10">
                <button class="px-6 py-3 rounded-lg border border-primary-500 text-primary-500 hover:bg-primary-500/10 font-medium">
                    View All Tasks
                </button>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 px-4 bg-gray-100">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12">What Our Users Say</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-secondary-500 flex items-center justify-center mr-4">
                            <i data-feather="user" class="text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-bold">Lisa T.</h3>
                            <div class="flex">
                                <i data-feather="star" class="text-yellow-400 w-4"></i>
                                <i data-feather="star" class="text-yellow-400 w-4"></i>
                                <i data-feather="star" class="text-yellow-400 w-4"></i>
                                <i data-feather="star" class="text-yellow-400 w-4"></i>
                                <i data-feather="star" class="text-yellow-400 w-4"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"Found someone to assemble my furniture within an hour of posting. Super affordable and professional!"</p>
                </div>
                <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-secondary-500 flex items-center justify-center mr-4">
                            <i data-feather="user" class="text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-bold">Mark R.</h3>
                            <div class="flex">
                                <i data-feather="star" class="text-yellow-400 w-4"></i>
                                <i data-feather="star" class="text-yellow-400 w-4"></i>
                                <i data-feather="star" class="text-yellow-400 w-4"></i>
                                <i data-feather="star" class="text-yellow-400 w-4"></i>
                                <i data-feather="star" class="text-yellow-400 w-4"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"Made $600 last month doing odd jobs in my free time. Perfect for students!"</p>
                </div>
                <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-secondary-500 flex items-center justify-center mr-4">
                            <i data-feather="user" class="text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-bold">Sarah K.</h3>
                            <div class="flex">
                                <i data-feather="star" class="text-yellow-400 w-4"></i>
                                <i data-feather="star" class="text-yellow-400 w-4"></i>
                                <i data-feather="star" class="text-yellow-400 w-4"></i>
                                <i data-feather="star" class="text-yellow-400 w-4"></i>
                                <i data-feather="star" class="text-yellow-400 w-4"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"Found a reliable dog walker when my regular one was sick. Lifesaver!"</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 px-4 bg-gradient-to-br from-primary-500 to-secondary-500">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-white mb-6">Ready to get things done?</h2>
            <p class="text-white/90 mb-8">Join thousands of people helping each other in their communities.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <button class="px-6 py-3 rounded-lg bg-white text-primary-500 hover:bg-gray-100 font-medium">
                    Post a Task
                </button>
                <button class="px-6 py-3 rounded-lg border border-white text-white hover:bg-white/10 font-medium">
                    Browse Tasks
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-12 px-4 shadow-sm">
        <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <i data-feather="zap" class="text-secondary-500"></i>
                    <span class="text-xl font-bold text-gray-900">TaskHive</span>
                </div>
                <p class="text-gray-600">Connecting people to get things done since 2023.</p>
                <div class="flex space-x-4 mt-4">
                    <a href="#" class="text-gray-400 hover:text-white"><i data-feather="facebook"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i data-feather="twitter"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i data-feather="instagram"></i></a>
                </div>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 mb-4">For Taskers</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 hover:text-secondary-500">Browse Tasks</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-secondary-500">How It Works</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-secondary-500">Safety Tips</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-secondary-500">Tasker Resources</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 mb-4">For Posters</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 hover:text-secondary-500">Post a Task</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-secondary-500">Pricing</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-secondary-500">Safety Tips</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-secondary-500">Help Center</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 mb-4">Company</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 hover:text-secondary-500">About Us</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-secondary-500">Careers</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-secondary-500">Press</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-secondary-500">Contact</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto border-t border-gray-200 mt-12 pt-8 text-center text-gray-500">
            <p>© 2023 TaskHive. All rights reserved.</p>
        </div>
    </footer>

    <script>
        feather.replace();
        
        // Theme toggle
        const themeToggle = document.getElementById('theme-toggle');
        const html = document.documentElement;
        
        // Check for saved theme preference or use preferred color scheme
        if (localStorage.getItem('theme') === 'dark' || 
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
            themeToggle.innerHTML = feather.icons['sun'].toSvg();
        } else {
            html.classList.remove('dark');
            themeToggle.innerHTML = feather.icons['moon'].toSvg();
        }

        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            const isDark = html.classList.contains('dark');
            themeToggle.innerHTML = isDark ? feather.icons['sun'].toSvg() : feather.icons['moon'].toSvg();
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    </script>
</body>
</html>
