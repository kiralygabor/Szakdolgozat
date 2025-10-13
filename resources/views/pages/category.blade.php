    @extends('layout')

       @section('content')

<section class="bg-white py-12 border-b">
  <div class="max-w-7xl mx-auto px-6 text-center">
    <h1 class="text-4xl font-bold text-gray-900">Browse Categories</h1>
    <p class="text-gray-600 mt-2">Find the perfect service for your task</p>
  </div>
  <div class="max-w-7xl mx-auto px-6 mt-8 grid grid-cols-1 md:grid-cols-[340px_1fr] gap-6">
  <!-- Left: Categories list (scrollable) -->
  <aside class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-4 border-b">
      <h3 class="text-lg font-bold text-gray-900">Categories</h3>
    </div>
    <div class="max-h-[70vh] overflow-y-auto">
      <ul id="categories-list" class="divide-y">
        <li>
          <button data-key="home" class="w-full text-left px-4 py-3 hover:bg-gray-50 focus:bg-gray-50 transition">
            <span class="font-medium text-gray-900">Home Services</span>
            <div class="text-sm text-gray-500">Handyman, Plumbing, Electrical</div>
          </button>
        </li>
        <li>
          <button data-key="moving" class="w-full text-left px-4 py-3 hover:bg-gray-50 focus:bg-gray-50 transition">
            <span class="font-medium text-gray-900">Moving & Delivery</span>
            <div class="text-sm text-gray-500">Furniture Moving, Courier, Transport</div>
          </button>
        </li>
        <li>
          <button data-key="cleaning" class="w-full text-left px-4 py-3 hover:bg-gray-50 focus:bg-gray-50 transition">
            <span class="font-medium text-gray-900">Cleaning</span>
            <div class="text-sm text-gray-500">Home Cleaning, Deep Cleaning, Windows</div>
          </button>
        </li>
        <li>
          <button data-key="personal" class="w-full text-left px-4 py-3 hover:bg-gray-50 focus:bg-gray-50 transition">
            <span class="font-medium text-gray-900">Personal & Tutoring</span>
            <div class="text-sm text-gray-500">Lessons, Babysitting, Pet Care</div>
          </button>
        </li>
        <li>
          <button data-key="tech" class="w-full text-left px-4 py-3 hover:bg-gray-50 focus:bg-gray-50 transition">
            <span class="font-medium text-gray-900">Tech Help</span>
            <div class="text-sm text-gray-500">PC setup, Network, Smart devices</div>
          </button>
        </li>
      </ul>
    </div>
  </aside>

  <!-- Right: Category detail -->
  <div id="category-detail" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="w-full h-64 md:h-96 bg-gray-100">
      <img id="cat-image" src="https://via.placeholder.com/1200x600" alt="Category" class="w-full h-full object-cover">
    </div>
    <div class="p-6">
      <h2 id="cat-title" class="text-2xl font-bold text-gray-900">Home Services</h2>
      <p id="cat-desc" class="text-gray-700 mt-3">Handyman, plumbing, electrical repairs and more — get reliable help for your home improvements and fixes.</p>
      <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ url('tasks') }}" class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">I'm a Finder</a>
        <a href="{{ url('register') }}" class="px-5 py-3 rounded-xl border border-gray-300 text-gray-800 font-semibold hover:bg-gray-50">I'm a Tasker</a>
      </div>
    </div>
  </div>
</div>  
</section>

<script>
  (function(){
    var categories = {
      home: {
        title: 'Home Services',
        desc: 'Handyman, plumbing, electrical repairs and more — get reliable help for your home improvements and fixes.',
        image: 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1600&q=60'
      },
      moving: {
        title: 'Moving & Delivery',
        desc: 'Need a hand moving or delivering items? Find trusted help for small and large moves.',
        image: 'https://images.unsplash.com/photo-1582582621959-48d8f8f9c6c3?auto=format&fit=crop&w=1600&q=60'
      },
      cleaning: {
        title: 'Cleaning',
        desc: 'From deep cleans to routine tidying, hire cleaners for homes, offices, and more.',
        image: 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=1600&q=60'
      },
      personal: {
        title: 'Personal & Tutoring',
        desc: 'Language lessons, babysitting, pet care — get personalized help for everyday needs.',
        image: 'https://images.unsplash.com/photo-1518081461904-9ac3d1970b4a?auto=format&fit=crop&w=1600&q=60'
      },
      tech: {
        title: 'Tech Help',
        desc: 'PC and device setup, troubleshooting, and smart home configuration assistance.',
        image: 'https://images.unsplash.com/photo-1518779578993-ec3579fee39f?auto=format&fit=crop&w=1600&q=60'
      }
    };

    var list = document.getElementById('categories-list');
    var img = document.getElementById('cat-image');
    var title = document.getElementById('cat-title');
    var desc = document.getElementById('cat-desc');

    list.addEventListener('click', function(e){
      var btn = e.target.closest('button[data-key]');
      if (!btn) return;
      var key = btn.getAttribute('data-key');
      var cat = categories[key];
      if (!cat) return;
      title.textContent = cat.title;
      desc.textContent = cat.desc;
      img.src = cat.image;
      // active state styling
      Array.prototype.forEach.call(list.querySelectorAll('button[data-key]'), function(b){
        b.classList.remove('bg-gray-50');
      });
      btn.classList.add('bg-gray-50');
    });
  })();
</script>

@endsection