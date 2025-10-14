    @extends('layout')

       @section('content')

<section class="bg-white pt-4 pb-6 border-b">
  <div class="max-w-7xl mx-auto px-4 text-center">
    <h1 class="text-4xl font-bold text-gray-900">Browse Categories</h1>

  </div>
  <div class="max-w-7xl mx-auto px-4 mt-5 grid grid-cols-1 md:grid-cols-[320px_1fr] gap-4 items-start">
  <!-- Left: Categories list (scrollable) -->
  <aside class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden sticky top-4 self-start">
    <div class="p-3 border-b">
      <h3 class="text-lg font-bold text-gray-900">Categories</h3>
    </div>
    <div class="max-h-[70vh] overflow-y-auto">
      <ul id="categories-list" class="divide-y">
        @php
          $firstCategory = ($categories ?? collect())->first();
          $fallbackImage = 'https://via.placeholder.com/1200x600?text=Category';
        @endphp
        @foreach(($categories ?? []) as $category)
        <li>
          <button type="button" class="w-full text-left px-4 py-3 hover:bg-gray-50 focus:bg-gray-50 transition"
                  data-id="{{ $category->id }}"
                  data-name="{{ $category->name }}"
                  data-desc="{{ $category->description ?? '' }}"
                  data-image="{{ $category->image_url ?? $fallbackImage }}">
            <span class="font-medium text-gray-900">{{ $category->name }}</span>
          </button>
        </li>
        @endforeach
      </ul>
    </div>
  </aside>

  <!-- Right: Category detail -->
  <div id="category-detail" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="w-full h-56 md:h-96 bg-gray-100">
      <img id="cat-image" src="{{ $firstCategory->image_url ?? $fallbackImage }}" alt="Category" class="w-full h-full object-cover">
    </div>
    <div class="p-6">
      <h2 id="cat-title" class="text-2xl font-bold text-gray-900">{{ $firstCategory->name ?? 'Categories' }}</h2>
      <p id="cat-desc" class="text-gray-700 mt-3">{{ $firstCategory->description ?? 'Pick a category to see details.' }}</p>
      <div class="mt-6 flex flex-wrap gap-3">
        <a id="finder-link" href="{{ url('tasks') }}@if(isset($firstCategory))?category={{ $firstCategory->id }}@endif" class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700">I'm a Finder</a>
        <a href="{{ url('register') }}" class="px-5 py-3 rounded-xl border border-gray-300 text-gray-800 font-semibold hover:bg-gray-50">I'm a Tasker</a>
      </div>
    </div>
  </div>
</div>  
</section>

<script>
  (function(){
    var list = document.getElementById('categories-list');
    var img = document.getElementById('cat-image');
    var title = document.getElementById('cat-title');
    var desc = document.getElementById('cat-desc');
    var finder = document.getElementById('finder-link');
    var selectedId = null;

    function selectFromButton(btn){
      selectedId = btn.getAttribute('data-id');
      var name = btn.getAttribute('data-name');
      var d = btn.getAttribute('data-desc') || 'Explore services tailored to this category.';
      var image = btn.getAttribute('data-image');
      title.textContent = name;
      desc.textContent = d;
      img.src = image;
      if (finder){
        var base = "{{ url('tasks') }}";
        finder.href = selectedId ? (base + '?category=' + selectedId) : base;
      }
      Array.prototype.forEach.call(list.querySelectorAll('button[data-id]'), function(b){
        b.classList.remove('bg-gray-50');
      });
      btn.classList.add('bg-gray-50');
    }

    // Init with the first item
    var firstBtn = list.querySelector('button[data-id]');
    if (firstBtn){
      selectFromButton(firstBtn);
    }

    list.addEventListener('click', function(e){
      var btn = e.target.closest('button[data-id]');
      if (!btn) return;
      selectFromButton(btn);
    });
  })();
</script>

@endsection