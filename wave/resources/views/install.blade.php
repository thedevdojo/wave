@if(app()->isLocal())

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Wave Installation</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.4/tailwind.min.css">
    </head>
    <body class="bg-zinc-50">

        @if(Request::get('complete'))

            @php
            

                \Illuminate\Support\Facades\Artisan::call('db:seed', [
                '--force' => true
                ]);

                \Illuminate\Support\Facades\Artisan::call('storage:link');

                @endphp

                <div class="flex flex-col justify-center items-center w-screen h-screen">
                <svg class="-mt-12 w-9 h-9" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 151 146" fill="none"><path fill="#006EFF" d="M4.062 145.905h36.147a4 4 0 0 0 4-4v-36.146a4 4 0 0 0-4-4H4.062a4 4 0 0 0-4 4v36.146a4 4 0 0 0 4 4ZM57.037 145.908h36.147a4 4 0 0 0 4-4v-36.147a4 4 0 0 0-4-4H57.037a4 4 0 0 0-4 4v36.147a4 4 0 0 0 4 4Z"/><path fill="#009AFF" d="M57.038 95.138h36.147a4 4 0 0 0 4-4V54.99a4 4 0 0 0-4-4H57.038a4 4 0 0 0-4 4v36.147a4 4 0 0 0 4 4Z"/><path fill="#00CEFF" d="M110.013 95.138h36.147a4 4 0 0 0 4-4V54.99a4 4 0 0 0-4-4h-36.147a4 4 0 0 0-4 4v36.147a4 4 0 0 0 4 4ZM110.014 44.367h36.147a4 4 0 0 0 4-4V4.221a4 4 0 0 0-4-4h-36.147a4 4 0 0 0-4 4v36.146a4 4 0 0 0 4 4Z"/></svg>
                <div class="flex flex-col items-center p-10 mx-auto mt-8 w-full max-w-lg bg-white rounded-xl border shadow-xl border-zinc-100">
                    <h1 class="text-3xl font-semibold text-green-400">Successfully Installed 🎉</h1>
                    <p class="mt-5 text-zinc-500">Click the continue button below to view your new SAAS application.</p>
                    <a href="/" class="flex justify-center px-4 py-2 mt-8 w-full text-lg font-medium text-white bg-blue-600 rounded-md border border-transparent transition duration-150 ease-in-out hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-wave active:bg-blue-700">
                        Continue
                    </a>
                </div>
            </div>

        @else

            @php
                \Illuminate\Support\Facades\File::put(database_path('database.sqlite'), '');
                \Illuminate\Support\Facades\Artisan::call('migrate', [
                    '--force' => true
                    ]);
            @endphp

            <div class="flex flex-col justify-center items-center w-screen h-screen">
                <svg class="-mt-12 w-9 h-9" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 151 146" fill="none"><path fill="#006EFF" d="M4.062 145.905h36.147a4 4 0 0 0 4-4v-36.146a4 4 0 0 0-4-4H4.062a4 4 0 0 0-4 4v36.146a4 4 0 0 0 4 4ZM57.037 145.908h36.147a4 4 0 0 0 4-4v-36.147a4 4 0 0 0-4-4H57.037a4 4 0 0 0-4 4v36.147a4 4 0 0 0 4 4Z"/><path fill="#009AFF" d="M57.038 95.138h36.147a4 4 0 0 0 4-4V54.99a4 4 0 0 0-4-4H57.038a4 4 0 0 0-4 4v36.147a4 4 0 0 0 4 4Z"/><path fill="#00CEFF" d="M110.013 95.138h36.147a4 4 0 0 0 4-4V54.99a4 4 0 0 0-4-4h-36.147a4 4 0 0 0-4 4v36.147a4 4 0 0 0 4 4ZM110.014 44.367h36.147a4 4 0 0 0 4-4V4.221a4 4 0 0 0-4-4h-36.147a4 4 0 0 0-4 4v36.146a4 4 0 0 0 4 4Z"/></svg>
                <div class="flex flex-col items-center p-10 mx-auto mt-8 w-full max-w-lg bg-white rounded-3xl border shadow-xl border-zinc-100">
                    <h1 class="text-3xl font-semibold text-blue-600">Welcome to Wave</h1>
                    <p class="mt-5 text-zinc-500">If you're ready to get started, click on the 'Install Wave' button below. In this future this installation screen will have a few setup options.</p>
                    <a href="/install?complete=true" class="flex justify-center px-4 py-2 mt-8 w-full text-lg font-medium text-white bg-blue-600 rounded-md border border-transparent transition duration-150 ease-in-out hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-wave active:bg-blue-700">
                        Install Wave
                    </a>
                </div>
            </div>

        @endif

    </body>
    </html>


@endif
