@props([
    'title' => 'Notification Title',
    'type' => 'default', // default, info, success, warning, danger
    'size' => 'default' // normal, sm
])

@php

    $alertIcon = 'phosphor-info-duotone';

    $alertIcon = match($type)
    {
        'default' => 'phosphor-chat-teardrop-text',
        'info' => 'phosphor-info-duotone',
        'success' => 'phosphor-check-circle-duotone',
        'warning' => 'phosphor-warning-duotone',
        'danger' => 'phosphor-warning-circle-duotone',
    };


@endphp

<div
    x-data="{ closed: false }"
    x-show="!closed"
    @class([
        'rounded-lg min-w-[300px] flex flex-col justify-center items-center w-full ',
        'p-3' => $size == 'sm',
        'p-4' => $size != 'sm',
        'bg-white dark:bg-gray-900 border shadow-sm border-gray-200 dark:border-gray-800 dark:text-gray-300 text-gray-600' => $type == 'default',
		'bg-indigo-50 text-indigo-700 border border-indigo-300' => $type == 'info',
		'bg-green-50 text-green-700 border border-green-300' => $type == 'success',
		'bg-yellow-50 text-yellow-700 border border-yellow-300' => $type == 'warning',
		'bg-red-50 text-red-600 border border-red-300' => $type == 'danger'
    ])
>
    <h6 class="flex items-center self-stretch justify-between flex-1 w-full gap-2">
        <span class="flex items-center flex-1 gap-2">
            <x-dynamic-component :component="$alertIcon" class="w-6 h-6"></x-dynamic-component>
            <span>{{ $title }}</span>
        </span>
        <span
			@class([
				'w-6 h-6 cursor-pointer rounded-full flex items-center justify-center',
				'hover:bg-indigo-100' => $type == 'info',
				'hover:bg-green-100' => $type == 'success',
				'hover:bg-yellow-100' => $type == 'warning',
				'hover:bg-coral-100' => $type == 'danger'
			])
			@click="closed=true"
		>

            <x-phosphor-x-bold class="w-4 h-4 dark:text-gray-600 hover:dark:text-gray-400" />
        </span>
    </h6>
    @if ($size != 'sm')
        <div class="flex items-start self-stretch justify-start w-full gap-0 pl-8 pr-10">
            <p class="flex items-center text-sm font-normal leading-6 opacity-70">{{ $slot }}</p>
        </div>
    @endif
</div>