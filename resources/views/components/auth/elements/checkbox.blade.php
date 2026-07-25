@props([
    'label' => null,
    'name' => null,
    'id' => null,
])

<div class="flex items-center h-5">
    <input type="checkbox" {{ $attributes->whereStartsWith('wire:model') }} id="{{ $id ?? '' }}" name="{{ $name ?? '' }}" class="hidden peer">
    <label for="{{ $id ?? '' }}" class="[&_svg]:peer-checked:scale-100 text-sm font-medium text-neutral-600 dark:text-neutral-400 dark:peer-checked:text-gray-300 peer-checked:text-gray-800 [&_svg]:scale-0 [&_.custom-checkbox]:peer-checked:border-gray-800 [&_.custom-checkbox]:peer-checked:bg-gray-800 dark:[&_.custom-checkbox]:peer-checked:bg-white select-none flex items-center space-x-2">
        <span class="flex justify-center items-center w-5 h-5 rounded-sm border border-gray-300 dark:border-gray-700 custom-checkbox text-neutral-900">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3 h-3 text-white duration-300 ease-out dark:text-gray-800">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
        </span>
        <span>{{ $label ?? '' }}</span>
    </label>
</div>