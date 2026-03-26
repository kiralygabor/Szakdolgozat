@extends('layout')
 
@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col items-center pt-10 pb-20">
    <div class="w-full max-w-4xl px-6 bg-white py-12 px-8 rounded-xl shadow-sm">
        <h1 class="text-4xl font-bold text-blue-900 mb-8">Community Guidelines</h1>
       
        <div class="prose max-w-none text-gray-700 space-y-6">
            <p>Welcome to our community! To ensure a safe, welcoming, and productive environment for all users, we ask you to follow these guidelines.</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">Respect and Professionalism</h2>
            <p>Treat everyone with respect. Do not engage in harassment, bullying, or discrimination against any individual based on race, ethnicity, religion, gender, sexual orientation, or disability.</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">Honest Communication</h2>
            <p>Be truthful in your profiles, task descriptions, and communication with others. Do not mislead taskers or task posters regarding the nature of the work, expected payment, or your abilities.</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">Safety First</h2>
            <p>Prioritize your safety and the safety of others. If a task requires specialized skills, licensing, or safety equipment, state it clearly. Do not post or accept tasks that involve illegal activities.</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">Reliability and Commitment</h2>
            <p>Honor your commitments. Show up on time and communicate promptly if there are any changes or delays. Your reliability affects your reputation on Minijobz.</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">Reporting Violations</h2>
            <p>If you encounter behavior that violates these guidelines, please report it immediately using our reporting tools. We take all reports seriously and will investigate promptly.</p>
           
            <p class="text-sm text-gray-500 mt-12 bg-gray-50 p-4 rounded text-center">Last updated: {{ date('Y-m-d') }}</p>
        </div>
    </div>
</div>
@endsection