{{-- Use favicon according to site settings and color scheme --}}
<link rel="icon" href="{{ setting('site.favicon', '/wave/favicon.png') }}" type="image/x-icon">
<link rel="icon" href="{{ setting('site.favicon_dark', '/wave/favicon-dark.png') }}" type="image/png" media="(prefers-color-scheme: dark)">
{{-- Optionally, you can use the favicon as an image: --}}
<img 
    {{ $attributes->merge(['class' => 'text-gray-900 dark:text-white', 'style' => 'width:64px;height:64px;']) }} 
    src="{{ setting('site.favicon', '/wave/favicon.png') }}" 
    alt="Logo" 
/>