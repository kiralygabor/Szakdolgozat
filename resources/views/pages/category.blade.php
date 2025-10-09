    @extends('layout')

       @section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Minijobz - Categories</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
  <!-- Navbar Placeholder -->
  <nav class="bg-white shadow p-4">
    <div class="container mx-auto flex justify-between items-center">
      <div class="text-xl font-bold text-secondary-500">Minijobz</div>
      <a href="#" class="text-gray-600 hover:text-secondary-500">Home</a>
    </div>
  </nav>

  <!-- Header -->
  <header class="text-center py-12 bg-white shadow-sm">
    <h1 class="text-4xl font-bold text-gray-900">Browse Categories</h1>
    <p class="text-gray-600 mt-2">Find the perfect service for your task</p>
  </header>

  <!-- Categories Grid -->
  <section class="container mx-auto py-12 px-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Example Category Card -->
    <div class="bg-white p-6 rounded-lg shadow hover:shadow-md transition cursor-pointer">
      <h3 class="text-lg font-semibold text-gray-900">Home Services</h3>
      <p class="text-gray-600 text-sm mt-2">Handyman, Plumbing, Electrical</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow hover:shadow-md transition cursor-pointer">
      <h3 class="text-lg font-semibold text-gray-900">Moving & Delivery</h3>
      <p class="text-gray-600 text-sm mt-2">Furniture Moving, Courier, Transport</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow hover:shadow-md transition cursor-pointer">
      <h3 class="text-lg font-semibold text-gray-900">Cleaning</h3>
      <p class="text-gray-600 text-sm mt-2">Home Cleaning, Deep Cleaning, Windows</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow hover:shadow-md transition cursor-pointer">
      <h3 class="text-lg font-semibold text-gray-900">Personal & Tutoring</h3>
      <p class="text-gray-600 text-sm mt-2">Lessons, Babysitting, Pet Care</p>
    </div>
  </section>

  <!-- Footer -->
  <footer class="text-center py-6 text-gray-500 text-sm">
    © 2025 Minijobz. All rights reserved.
  </footer>
</body>
</html>

@endsection