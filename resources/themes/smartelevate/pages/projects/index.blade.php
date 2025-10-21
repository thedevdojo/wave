<?php
    use function Laravel\Folio\{middleware, name};
    name('dashboard');
?>

<x-layouts.app>

    <x-app.heading
        title="Projects"
        description="You can eaily modify this file to add your content here."
    />

    <x-app.container class="mt-5">
        <x-app.alert title="Example Page">
            This is an example page that you can modify from the `resources/themes/drift/pages/projects/index.blade.php` file.
        </x-app.alert>
        <div class="grid w-full grid-cols-1 gap-5 py-5 sm:grid-cols-2 lg:grid-cols-3">
            <x-app.project
                title="Project Name Here"
                url="project-url.com"
                repo="username/repository"
                :skeleton="false"
            />
            <x-app.project
                title="Project 2"
                url="project-url.com"
                repo="username/repository"
                :skeleton="true"
            />
            <x-app.project
                title="Project 3"
                url="project-url.com"
                repo="username/repository"
                :skeleton="true"
            />
            <x-app.project
                title="Project 4"
                url="project-url.com"
                repo="username/repository"
                :skeleton="true"
            />
            <x-app.project
                title="Project 5"
                url="project-url.com"
                repo="username/repository"
                :skeleton="true"
            />
            <x-app.project
                title="Project 6"
                url="project-url.com"
                repo="username/repository"
                :skeleton="true"
            />
        </div>
        
    </x-app.container>

</x-layouts.app>