@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm p-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Subscription Settings</h1>
            
            @if(auth()->user()->subscription)
                <div class="mb-6">
                    <p class="text-gray-600 dark:text-gray-300">
                        You are currently subscribed to the <span class="font-medium">{{ auth()->user()->subscription->plan->name }}</span> Monthly Plan.
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                        Manage your subscription by clicking below.
                    </p>
                </div>

                <a href="{{ route('billing') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Manage Subscription
                </a>
            @else
                <div class="text-center">
                    <p class="text-gray-600 dark:text-gray-300 mb-4">You are not currently subscribed to any plan.</p>
                    <a href="{{ route('billing') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Choose a Plan
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 