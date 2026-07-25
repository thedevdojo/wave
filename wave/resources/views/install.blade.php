@if(app()->isLocal())

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Wave Installation</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-zinc-50">

        @if(Request::get('complete'))

            @php
                \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);

                if (! \Illuminate\Support\Facades\File::exists(public_path('storage'))) {
                    \Illuminate\Support\Facades\Artisan::call('storage:link');
                }

                \Illuminate\Support\Facades\Auth::login(\App\Models\User::first());

                $composerDir = public_path('composer');

                if (\Illuminate\Support\Facades\File::isDirectory($composerDir)) {
                    \Illuminate\Support\Facades\File::deleteDirectory($composerDir);
                }
            @endphp

            <div class="flex flex-col justify-center items-center w-screen h-screen">
                @include('wave::partials.logo-mark', ['class' => '-mt-12'])
                <div class="flex flex-col items-center p-10 mx-auto mt-8 w-full max-w-lg bg-white rounded-xl border shadow-xl border-zinc-100">
                    <h1 class="text-2xl font-semibold text-black">Successfully Installed 🎉</h1>
                    <p class="mt-5 text-zinc-500">Click the continue button below to view your new SAAS application.</p>
                    <a href="/" class="flex justify-center px-4 py-2 mt-8 w-full text-lg font-medium text-white bg-gray-900 rounded-md border border-transparent transition duration-150 ease-in-out hover:bg-gray-800">
                        Continue
                    </a>
                </div>
            </div>

        @else

            @php
                try {
                    if (\App\Models\User::first()) {
                        header('Location: /');
                        exit;
                    }
                } catch (\Illuminate\Database\QueryException $e) {
                    // Continue with the installation process.
                }

                $envPath = base_path('.env');

                if (\Illuminate\Support\Facades\File::exists($envPath)) {
                    $envContents = \Illuminate\Support\Facades\File::get($envPath);
                    $appUrl = rtrim(url('/'), '/');

                    if (\Illuminate\Support\Str::contains($envContents, 'APP_URL=')) {
                        $envContents = preg_replace('/^APP_URL=.*/m', 'APP_URL='.$appUrl, $envContents);
                    } else {
                        $envContents .= PHP_EOL.'APP_URL='.$appUrl;
                    }

                    \Illuminate\Support\Facades\File::put($envPath, $envContents);
                }

                if (empty(config('app.key'))) {
                    \Illuminate\Support\Facades\Artisan::call('key:generate', ['--force' => true]);
                }

                $databaseDir = dirname(database_path('database.sqlite'));

                if (! \Illuminate\Support\Facades\File::exists($databaseDir)) {
                    \Illuminate\Support\Facades\File::makeDirectory($databaseDir, 0755, true);
                }

                if (! \Illuminate\Support\Facades\File::exists(database_path('database.sqlite'))) {
                    \Illuminate\Support\Facades\File::put(database_path('database.sqlite'), '');
                }

                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            @endphp

            <div class="flex flex-col justify-center items-center w-screen h-screen">
                @include('wave::partials.logo-mark', ['class' => '-mt-12 w-9 h-9'])
                <div class="flex flex-col items-center p-10 mx-auto mt-8 w-full max-w-lg bg-white rounded-2xl border shadow-sm border-zinc-100">
                    <h1 class="text-2xl font-semibold text-black">Welcome to Wave</h1>
                    <p class="mt-5 text-center text-zinc-500">
                        Composer dependencies are installed. Click below to seed the database, publish assets, and finish setup.
                    </p>
                    <a href="/install?complete=true" class="flex justify-center px-4 py-2 mt-8 w-full text-lg font-medium text-white bg-gray-900 rounded-md border border-transparent transition duration-150 ease-in-out hover:bg-gray-800">
                        Install Wave
                    </a>
                </div>
            </div>

        @endif

    </body>
    </html>

@endif
