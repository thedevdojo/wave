@props([
    'circular' => true,
	'alt' => null,
    'size' => 'md',
	'indicator' => false,
	'badge' => false,
	'badgeText' => null,
	'groupSrcs' => null,
    'src' => '',
	'srcset' => '',
	'user' => auth()->user()
])

@php
	$avatarSize = match($size)
	{
		'2xs' => 'size-6',
		'xs' => 'size-8',
		'sm' => 'size-12',
		'md' => 'size-16',
		'lg' => 'size-24',
		'xl' => 'size-32',
		default => 'size-16',
	};

	$groupMargin = match($size){
		'xs' => '-ml-3',
		'sm' => '-ml-5',
		'md' => '-ml-7',
		default => '-ml-7',
	};
@endphp

<div
	@class([
		$avatarSize,
		'relative' => empty($groupSrcs),
		'flex' => !empty($groupSrcs)
	])
	>
	@if (filter_var($badge, FILTER_VALIDATE_BOOLEAN) && ($size == 'sm' || $size == 'xs') && !empty($badgeText))
		<x-filament::badge
			@class([
				"absolute size-6 !rounded-full bg-purple-600 border-white border-2 right-0 !p-0 text-white",
				"translate-x-1/2 -translate-y-1/2" => $size == 'xs',
				"translate-x-1/3 -translate-y-1/3" => $size == 'sm',
			])
		>
			{{ $badgeText }}
		</x-badge>
	@endif

	@if (!empty($groupSrcs))
		@for ($i = 0; $i < count($groupSrcs); $i++)
			@if ($i >= 4)
				<span
					@class([
						'flex rounded-full size-full justify-center items-center text-white shrink-0 font-semibold bg-berry-blue',
						$avatarSize,
						$groupMargin,
						'text-xs' => $size == '2xs',
						'text-sm' => $size == 'xs',
						'text-xl' => $size == 'sm',
						'text-2xl' => $size == 'md',
						'text-4xl' => $size == 'lg',
						'text-5xl' => $size == 'xl',
					])
				>
					{{ '+' . (count($groupSrcs) - $i) }}
				</span>
				@break
			@else
				<x-filament::avatar
					:src="$groupSrcs[$i]['src']"
					:srcset="$groupSrcs[$i]['srcset']"
					:alt="($groupSrcs[$i]['alt'] ?? $alt)"
					:size="$avatarSize"
					:circular="$circular"
					:class="(($i != 0) ? $groupMargin : '')"
				/>
			@endif

		@endfor
	@else
		<x-filament::avatar
            x-data="{ src: '', refreshAvatarSrc(){ this.src='{{ $src }}' + '?' + new Date().getTime() } }" 
            x-init="refreshAvatarSrc(); $nextTick(function(){ $el.style.display='block'; })" 
            @refresh-avatar.window="refreshAvatarSrc()" 
            x-bind:src="src"
			:alt="$alt"
			:size="$avatarSize"
			:circular="$circular"
		    style="display:none" />
	@endif

    @if (filter_var($indicator, FILTER_VALIDATE_BOOLEAN) && $size != '2xs')
		<span
			@class([
				'absolute bottom-0 right-0 rounded-full border-white bg-green-500',
				'size-2.5 border' => $size == 'xs',
				'size-3.5 border' => $size == 'sm',
				'size-5 border-2' => $size == 'md',
				'size-7 border-2' => $size == 'lg',
				'size-9 border-2' => $size == 'xl',
			])
		></span>
    @endif
</div>