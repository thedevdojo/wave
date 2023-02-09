@extends('theme::layouts.app')

@section('content')

    <div class="max-w-4xl px-5 mx-auto mt-10 lg:px-0">
        <span class="flex items-center mb-6 font-mono text-sm font-bold text-gray-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
            All Announcements
        </span>
    </div>
    
    <div class="max-w-4xl px-5 pb-20 mx-auto prose prose-xl lg:prose-2xl lg:px-0">

        <h1>Announcements</h1>

        <div class="pb-8 my-8 border-b border-gray-200">

            @foreach($announcements as $announcement)

                <a href="{{ route('wave.announcement', $announcement->id) }}">{{ $announcement->title }}</a>
                <span class="block mt-0 text-gray-600">{{ $announcement->description }}</span>

            @endforeach

        </div>

    </div>

@endsection