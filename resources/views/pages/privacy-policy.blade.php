@extends('layout')
 
@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col items-center pt-10 pb-20">
    <div class="w-full max-w-4xl px-6 bg-white py-12 px-8 rounded-xl shadow-sm">
        <h1 class="text-4xl font-bold text-blue-900 mb-8">Privacy Policy</h1>
       
        <div class="prose max-w-none text-gray-700 space-y-6">
            <p>Your privacy is critically important to us. At Minijobz, we have a few fundamental principles regarding user privacy.</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">Information We Collect</h2>
            <p>We only ask you for personal information when we truly need it to provide a service to you. We collect information by fair and lawful means, with your knowledge and consent.</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">How We Use Your Information</h2>
            <p>We use the information we collect to operate, provide, maintain, and improve our services, communicate with you, process transactions, and prevent fraudulent activities.</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">Information Sharing</h2>
            <p>We do not share your personal information publicly or with third-parties, except when required to by law, or to provide our core services via trusted partners.</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">Data Security</h2>
            <p>We protect your personal information within commercially acceptable means to prevent loss and theft, as well as unauthorized access, disclosure, copying, use, or modification.</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">Your Rights</h2>
            <p>You have the right to request access to, update, or delete your personal information at any time. You can do this through your account settings or by contacting our support team.</p>
           
            <p class="text-sm text-gray-500 mt-12 bg-gray-50 p-4 rounded text-center">Last updated: {{ date('Y-m-d') }}</p>
        </div>
    </div>
</div>
@endsection