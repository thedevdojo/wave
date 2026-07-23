@props([
    'label' => null,
    'id' => null,
    'name' => null,
    'type' => 'text',
    'autofocus' => false
])

@php $wireModel = $attributes->get('wire:model'); @endphp

<div x-data="{ 
        focusedOrFilled: false,
        focused(){
            this.focusedOrFilled=true;
        },
        blurred() {
            if(this.$refs.input.value == ''){
                this.focusedOrFilled=false;
            }
        }
    }" x-init="@if($autofocus ?? false) setTimeout(function(){ $refs.input.focus(); }, 1); @endif " class="w-full h-auto">
    <div class="flex relative flex-col justify-center h-11">
        <div class="flex relative">
            @if($label)
                <label 
                    for="{{ $id ?? '' }}"
                    @click="$refs.input.focus()"
                    :class="{ 'top-0 -translate-y-1 ml-2 text-xs auth-component-input-label-focused' : focusedOrFilled, 'top-[16px] ml-2.5 text-[15px] text-gray-500' : !focusedOrFilled }"
                    class="block absolute top-0 px-1.5 py-0 font-normal leading-normal bg-white duration-300 ease-out cursor-text auth-component-input dark:text-gray-300" x-cloak>
                    {{ $label  }}
                </label>
            @endif

            <div data-model="{{ $wireModel }}" class="mt-1.5 w-full rounded-md shadow-xs auth-component-input-container">
                <input {{ $attributes }} {{ $attributes->whereStartsWith('wire:model') }} @focus-{{ $id }}.window="$el.focus()" id="{{ $id ?? '' }}" name="{{ $name ?? '' }}" type="{{ $type ?? '' }}" x-ref="input" @focus="focused()" @blur="blurred()" class="auth-component-input appearance-none flex w-full h-11 px-3.5 text-sm bg-white border rounded-md border-gray-300 placeholder:text-gray-500 focus:outline-hidden focus:ring-1 focus:ring-zinc-800 disabled:cursor-not-allowed disabled:opacity-50 @error($wireModel) border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:ring-red @enderror" />
            </div>
        </div>
    </div>

    @error($wireModel)
        <p class="my-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>