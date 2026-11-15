<span wire:poll.30s="refreshCount">
    @if ($count > 0)
        <span class="ml-1 inline-flex min-w-5 items-center justify-center rounded-full bg-primary-600 px-1.5 py-0.5 text-xs font-semibold text-white" aria-label="{{ $count }} unread notifications">{{ $count > 99 ? '99+' : $count }}</span>
    @endif
</span>
