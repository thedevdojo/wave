<div class="w-full h-auto">
    <form wire:submit="save" class="w-full">
        <div class="mt-8 w-full">
            {{ $this->form }}
        </div>
        <div class="pt-6 w-full text-right">
            <x-button type="submit">Salvare</x-button>
        </div>
    </form>
</div>
