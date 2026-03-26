@extends('layout')
 
@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col items-center pt-10 pb-20">
    <div class="w-full max-w-4xl px-6 bg-white py-12 px-8 rounded-xl shadow-sm">
        <h1 class="text-4xl font-bold text-blue-900 mb-8">Terms & Conditions</h1>
       
        <div class="prose max-w-none text-gray-700 space-y-6">
            <p>Welcome to Minijobz. By accessing or using our platform, you agree to be bound by these Terms & Conditions. Please read them carefully.</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">1. Acceptance of Terms</h2>
            <p>By registering for an account, posting a task, or placing an offer, you agree to these terms. If you do not agree, you may not use our services.</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">2. User Responsibilities</h2>
            <ul class="list-disc pl-6 space-y-2">
                <li>You must provide accurate information when creating an account.</li>
                <li>You are responsible for maintaining the security of your account credentials.</li>
                <li>You agree not to use the platform for any illegal or unauthorized purpose.</li>
            </ul>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">3. Tasks and Offers</h2>
            <p>Minijobz acts as a platform connecting users who need tasks done with those willing to do them. We are not a party to the actual contract between the task poster and the tasker.</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">4. Limitation of Liability</h2>
            <p>To the fullest extent permitted by law, Minijobz shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising out of your use of the platform.</p>
           
            <p class="text-sm text-gray-500 mt-12 bg-gray-50 p-4 rounded text-center">Last updated: {{ date('Y-m-d') }}</p>
        </div>
    </div>
</div>
@endsection