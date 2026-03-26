@extends('layout')
 
@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col items-center pt-10 pb-20">
    <div class="w-full max-w-4xl px-6 bg-white py-12 px-8 rounded-xl shadow-sm">
        <h1 class="text-4xl font-bold text-blue-900 mb-8">Help / FAQ</h1>
       
        <div class="prose max-w-none text-gray-700 space-y-6">
            <p>Welcome to our Help and FAQ page. Find answers to the most common questions below.</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">How do I post a task?</h2>
            <p>Simply navigate to the "Post a Task" button, fill in the details of what you need done, and publish it. Taskers will start making offers shortly.</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">How do I get paid?</h2>
            <p>Payments are currently arranged between the person completing the task and the person who posted it. Make sure to discuss payment details clearly before starting the work.</p>
           
            <h2 class="text-2xl font-semibold text-blue-800 mt-8 mb-4">Can I cancel an offer?</h2>
            <p>Yes, you can cancel an offer before it has been accepted by the task poster. Once accepted, we encourage you to communicate with the other party to resolve any issues.</p>
           
            <p class="text-sm text-gray-500 mt-12 bg-gray-50 p-4 rounded text-center">Last updated: {{ date('Y-m-d') }}</p>
        </div>
    </div>
</div>
@endsection
 