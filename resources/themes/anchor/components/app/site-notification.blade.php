@props(['notification'])
@php
    $data = $notification->data;
    $category = $data['category'] ?? '';
    $label = \Wave\NotificationBroadcast::categories()[$category] ?? 'Notification';
    $timezone = auth()->user()->profile('time-zone') ?: config('app.timezone');
    if (! in_array($timezone, timezone_identifiers_list(), true)) {
        $timezone = config('app.timezone');
    }
@endphp
<article wire:key="notification-{{ $notification->id }}" class="border-b border-zinc-200 py-5 dark:border-zinc-700">
    <div class="flex flex-wrap items-center gap-3">
        <span @class([
            'rounded-full px-2.5 py-1 text-xs font-semibold',
            'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200' => $category === 'security',
            'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-200' => $category === 'marketing',
            'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200' => ! in_array($category, ['security', 'marketing']),
        ])>{{ $label }}</span>
        <time datetime="{{ $notification->created_at->toIso8601String() }}" class="text-xs text-zinc-500 dark:text-zinc-400">{{ $notification->created_at->copy()->timezone($timezone)->format('M j, Y g:i A') }}</time>
    </div>
    <h3 class="mt-3 text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ $data['title'] ?? '' }}</h3>
    <div class="prose prose-sm mt-3 max-w-none dark:prose-invert">{!! \Illuminate\Support\Str::sanitizeHtml($data['body'] ?? '') !!}</div>
    <x-button type="button" color="gray" size="sm" icon="phosphor-check-duotone" wire:click="delete('{{ $notification->id }}')" wire:loading.attr="disabled" class="mt-4">Mark as read</x-button>
</article>
