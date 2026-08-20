@props(['order'])

@php
    $analytics = app(\App\Services\AnalyticsService::class);
    $fire = false;

    // Server-authoritative one-shot: the flag is set the first time the
    // confirmation page is rendered, so a refresh can never duplicate the event.
    if ($analytics->eventEnabled('orders') && ! $analytics->purchaseSent($order)) {
        $analytics->markPurchaseSent($order);
        $fire = true;
    }

    $items = $order->items->map(fn ($item) => [
        'item_id' => $item->menu_item_id,
        'item_name' => $item->item_name_snapshot,
        'quantity' => $item->quantity,
        'price' => (float) $item->unit_price_snapshot,
    ])->values()->all();
@endphp

@if ($fire)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.VanniyanAnalytics) {
            window.VanniyanAnalytics.push('purchase', {
                transaction_id: @js($order->reference),
                value: {{ (float) $order->total }},
                currency: @js(\App\Services\AnalyticsService::CURRENCY),
                items: @json($items)
            }, { consent: 'marketing' });
        }
    });
</script>
@endif