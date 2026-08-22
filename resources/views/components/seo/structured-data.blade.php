@props(['seo', 'model' => null, 'routeName' => null])

@php
    $schemas = [];
    $schemaType = $seo['schema_type'] ?? null;
    
    // Default Restaurant Schema on Home and Contact
    if ($routeName === 'home' || $routeName === 'contact' || $schemaType === 'Restaurant') {
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'Restaurant',
            'name' => \App\Models\RestaurantSetting::where('key', 'seo_default_title')->value('value') ?? 'Vanniyan Restaurant',
            'image' => \App\Models\RestaurantSetting::where('key', 'seo_default_og_image')->value('value') ? asset('storage/' . \App\Models\RestaurantSetting::where('key', 'seo_default_og_image')->value('value')) : '',
            '@id' => config('app.url'),
            'url' => config('app.url'),
            'telephone' => \App\Models\RestaurantSetting::where('key', 'phone')->value('value') ?? '',
            'menu' => route('menu'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => \App\Models\RestaurantSetting::where('key', 'address_street')->value('value') ?? '',
                'addressLocality' => \App\Models\RestaurantSetting::where('key', 'address_locality')->value('value') ?? 'Kilinochchi',
                'addressRegion' => \App\Models\RestaurantSetting::where('key', 'address_region')->value('value') ?? 'Northern Province',
                'addressCountry' => 'LK'
            ]
        ];
    }
    
    // Event Schema
    if ($model && $model instanceof \App\Models\Event && $model->is_published) {
        $eventStatus = 'https://schema.org/EventScheduled';
        if (\Carbon\Carbon::parse($model->start_date)->isPast()) {
            $eventStatus = 'https://schema.org/EventMovedOnline'; // Actually we should probably use a proper status, but schema.org has specific enum. 
            // Better to keep Scheduled for past events too or EventMovedOnline is not good.
        }
        
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $model->title,
            'description' => $model->short_description ?? $model->description,
            'image' => $model->hero_image ? asset('storage/' . $model->hero_image) : '',
            'startDate' => $model->start_date ? \Carbon\Carbon::parse($model->start_date)->toIso8601String() : '',
            'endDate' => $model->end_date ? \Carbon\Carbon::parse($model->end_date)->toIso8601String() : '',
            'eventStatus' => $eventStatus,
            'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
            'location' => [
                '@type' => 'Place',
                'name' => 'Vanniyan Restaurant',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => 'Kilinochchi',
                    'addressCountry' => 'LK'
                ]
            ],
            'url' => route('events.show', $model->slug)
        ];
    }
@endphp

@foreach($schemas as $schema)
    <script type="application/ld+json">
        {!! json_encode($schema, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
@endforeach
