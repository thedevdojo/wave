@auth
    <x-button href="{{ route('dashboard') }}" tag="a" size="sm" class="w-full mb-3 text-xs uppercase duration-300 ease-out md:mb-0 transform-gpu hover:ring-2 hover:ring-offset-2 hover:ring-gray-800 md:w-auto group dark:hover:ring-gray-200 dark:ring-offset-gray-900">View Dashboard</x-button>
@else
    <div class="relative flex items-center mb-3 space-x-1.5 lg:space-x-3 md:mb-0">
        <a href="{{ route('login') }}" class="flex-1 px-2 py-2 text-xs font-bold leading-none text-center text-gray-700 uppercase bg-gray-200 rounded dark:text-gray-300 md:bg-transparent md:flex-auto lg:px-4 dark:hover:text-gray-200 hover:text-gray-500">Log In</a>
        <x-button href="{{ route('register') }}" tag="a" size="sm" class="flex-1 text-xs uppercase duration-300 ease-out md:flex-auto transform-gpu hover:ring-2 hover:ring-offset-2 hover:ring-gray-800 group dark:hover:ring-gray-200 dark:ring-offset-gray-900">Sign Up</x-button>
    </div>
@endif