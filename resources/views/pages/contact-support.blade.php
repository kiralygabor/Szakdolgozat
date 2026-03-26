@extends('layout')
 
@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col items-center pt-10 pb-20">
    <div class="w-full max-w-4xl px-6 bg-white py-12 px-8 rounded-xl shadow-sm">
        <h1 class="text-4xl font-bold text-blue-900 mb-8">Contact / Support</h1>
       
        <div class="prose max-w-none text-gray-700 space-y-6">
            <p>If you're experiencing any issues with our platform, please reach out. We're here to help!</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">Email Support</h2>
            <p>You can reach our support team 24/7 by emailing <code>support@minijobz.com</code>. We typically reply within 24 hours.</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">Phone Support</h2>
            <p>Our phone lines are open from Monday to Friday, 9:00 AM to 5:00 PM (GMT).</p>
            <p>Call us at: +36 (30) 123 4567</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">Reporting a User</h2>
            <p>If you need to report inappropriate behavior, please use the specific 'Report' feature on the user's profile or task page so our team can review it immediately.</p>
           
            <p class="text-sm text-gray-500 mt-12 bg-gray-50 p-4 rounded text-center">Last updated: {{ date('Y-m-d') }}</p>
        </div>
    </div>
</div>
@endsection