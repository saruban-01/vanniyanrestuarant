@php
    $analytics = app(\App\Services\AnalyticsService::class);
@endphp

@if ($analytics->gtmEnabled() && $analytics->gtmContainerId() !== '')
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $analytics->gtmContainerId() }}"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif