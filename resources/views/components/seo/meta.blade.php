@props(['seo'])

@if($seo)
    <title>{{ $seo['meta_title'] }}</title>
    
    @if($seo['meta_description'])
        <meta name="description" content="{{ $seo['meta_description'] }}">
    @endif
    
    @if($seo['canonical_url'])
        <link rel="canonical" href="{{ $seo['canonical_url'] }}">
    @endif
    
    <meta name="robots" content="{{ $seo['robots'] ?? 'index, follow' }}">
    
    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $seo['og_title'] ?? $seo['meta_title'] }}">
    
    @if($seo['og_description'] || $seo['meta_description'])
        <meta property="og:description" content="{{ $seo['og_description'] ?? $seo['meta_description'] }}">
    @endif
    
    @if($seo['og_image'])
        <meta property="og:image" content="{{ $seo['og_image'] }}">
    @endif
    
    <meta property="og:url" content="{{ $seo['canonical_url'] ?? request()->url() }}">
    <meta property="og:type" content="website">
    
    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['og_title'] ?? $seo['meta_title'] }}">
    
    @if($seo['og_description'] || $seo['meta_description'])
        <meta name="twitter:description" content="{{ $seo['og_description'] ?? $seo['meta_description'] }}">
    @endif
    
    @if($seo['og_image'])
        <meta name="twitter:image" content="{{ $seo['og_image'] }}">
    @endif
@else
    <title>Vanniyan Restaurant</title>
    <meta name="robots" content="index, follow">
@endif
