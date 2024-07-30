<?php
    use function Laravel\Folio\{middleware, name};
    name('dashboard');
    middleware('wave');
?>

<x-layouts.app>
  <div class="px-4 mx-auto max-w-5xl">
    <h2 class="text-2xl font-bold">Welcome, {{ auth()->user()->name }} 👋</h2>
  </div>
  <div class="px-4 mx-auto mt-8 space-y-8 max-w-5xl">
      <div class="flex flex-wrap -m-4">
          <x-app.dashboard-card icon="phosphor-user" label="Users" value="143" change-direction="up" change-percentage="25"></x-app.dashboard-card>
          <x-app.dashboard-card icon="phosphor-money" label="Sales" value="54" change-direction="down" change-percentage="5"></x-app.dashboard-card>
          <x-app.dashboard-card icon="phosphor-arrows-clockwise" label="Refunds" value="3" change-direction="up" change-percentage="10"></x-app.dashboard-card>
      </div>
      <div class="p-10 space-y-4 w-full border border-black">
        <p>Welcome to your user dashboard. This file can be edited from your theme folder at <code class="px-2 py-1 text-xs bg-gray-100">pages/dashboard/index.blade.php</code></p>
        <p>This is a <a href="https://livewire.laravel.com/docs/volt" target="_blank" class="underline">Laravel Volt</a>   page. Learn more about <a href="https://devdojo.com/wave/docs/pages" target="_blank" class="underline">how pages work</a> here.</p>
      </div>
  </div>
</x-layouts.app>
