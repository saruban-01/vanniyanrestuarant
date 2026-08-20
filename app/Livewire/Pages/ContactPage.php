<?php

namespace App\Livewire\Pages;

use App\Models\ContactMessage;
use App\Models\SpecialHour;
use App\Services\RestaurantHoursService;
use App\Services\RestaurantSettingsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class ContactPage extends Component
{
    public $name = '';
    public $phone = '';
    public $email = '';
    public $subject = '';
    public $message = '';

    public $isSubmitted = false;

    protected $rules = [
        'name' => 'required|min:2',
        'phone' => 'required|min:9',
        'email' => 'nullable|email',
        'subject' => 'required',
        'message' => 'required|min:10',
    ];

    public function submitMessage()
    {
        $this->validate();

        // Rate Limiting (max 3 submissions per hour per IP)
        $executed = RateLimiter::attempt(
            'contact-form:' . request()->ip(),
            3,
            function() {
                ContactMessage::create([
                    'name' => $this->name,
                    'phone' => $this->phone,
                    'email' => $this->email,
                    'subject' => $this->subject,
                    'message' => $this->message,
                ]);
            },
            3600 // 1 hour
        );

        if (! $executed) {
            $this->addError('submit', 'Too many messages sent. Please try again later.');
            return;
        }

        // Trigger Admin Notification
        \App\Models\AdminNotification::notify(
            'NEW_CONTACT_MESSAGE',
            'New Contact Message',
            "From: {$this->name} — {$this->subject}"
        );

        $this->isSubmitted = true;
    }

    public function render(RestaurantSettingsService $settingsService, RestaurantHoursService $hoursService)
    {
        $settings = $settingsService->getAll();
        
        $isOpenNow = $hoursService->isOpenNow();
        $nextOpening = $hoursService->getNextOpeningTime();
        $weeklySchedule = $hoursService->getWeeklySchedule();
        
        // Check for special hours today
        $today = Carbon::today('Asia/Colombo')->format('Y-m-d');
        $specialToday = SpecialHour::where('date', $today)->first();

        // Pass structured data schema
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "Restaurant",
            "name" => $settings['name'] ?? 'Vanniyan Restaurant',
            "image" => "https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1600&q=80",
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => $settings['address'] ?? '',
                "addressLocality" => $settings['city'] ?? '',
                "addressRegion" => $settings['province'] ?? '',
                "postalCode" => $settings['postal_code'] ?? '',
                "addressCountry" => $settings['country'] ?? ''
            ],
            "geo" => [
                "@type" => "GeoCoordinates",
                "latitude" => $settings['latitude'] ?? '',
                "longitude" => $settings['longitude'] ?? ''
            ],
            "url" => url('/contact'),
            "telephone" => $settings['phone'] ?? '',
            "servesCuisine" => "Sri Lankan",
            "acceptsReservations" => "True"
        ];

        return view('livewire.pages.contact-page', [
            'settings' => $settings,
            'isOpenNow' => $isOpenNow,
            'nextOpening' => $nextOpening,
            'weeklySchedule' => $weeklySchedule,
            'specialToday' => $specialToday,
            'schemaJson' => json_encode($schema),
        ])->layout('components.layouts.app', [
            'title' => 'Contact Vanniyan Restaurant | Location & Opening Hours | Kilinochchi',
            'meta_description' => 'Find Vanniyan Restaurant in Kilinochchi, Sri Lanka. View our location, opening hours, contact details, takeaway information and table reservations.',
        ]);
    }
}
