@props([
    'title' => '',
    'type' => 'gray', // info, success, warning, danger
    'id' => uniqid(),
    'dismissable' => true
])

@php

    $alertIcon = 'phosphor-info-duotone';

    $alertIcon = match($type)
    {
        'info' => 'phosphor-info-duotone',
        'success' => 'icon-check-circle-duotone',
        'warning' => 'icon-warning-duotone',
        'danger' => 'icon-warning-circle-duotone',
        'gray' => 'icon-info-duotone'
    };


@endphp

<div 
    x-show="alert_{{ $id }}"
    x-data="{ alert_{{ $id }}: $persist(true) }"
    {{ $attributes->class([
        'relative pl-5 pr-10 py-4 w-full rounded-md border',
        'bg-gray-100 dark:bg-zinc-700 dark:border-zinc-600 text-gray-900 dark:text-gray-300 border-gray-200 dark:border-zinc-800' => $type == 'gray',
		'bg-blue-50 text-blue-600 border-blue-200' => $type == 'info',
		'bg-green-100 text-green-600 border-green-200' => $type == 'success',
		'bg-yellow-50 text-yellow-600 border-yellow-200' => $type == 'warning',
		'bg-red-50 text-red-600 border-red-200' => $type == 'danger'
    ]) }}
    x-collapse
    x-cloak
>
    @if($dismissable)
        <button @click="alert_{{ $id }}=false" class="absolute right-0 top-0 z-50 p-1.5 mr-3 rounded-full opacity-70 mt-3.5 cursor-pointer hover:opacity-100 hover:bg-zinc-200 hover:dark:bg-zinc-700 hover:dark:text-zinc-300 text-zinc-500 dark:text-zinc-400"><x-phosphor-x-bold class="w-3.5 h-3.5" /></button>
    @endif
    @if($title ?? false)
        <div class="flex items-start space-x-2">
            <x-icon name="{{ $alertIcon }}" class="w-5 h-5 -translate-y-0.5" />
            <h5 class="mb-1 font-medium leading-none tracking-tight">{{ $title }}</h5>
        </div>
    @endif
    <div class="@if($title ?? false){{ 'pl-7' }}@endif text-sm leading-6">{{ $slot }}</div>
    
</div>