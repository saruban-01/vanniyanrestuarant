<?php

namespace App\Services;

use App\Models\VenueBooking;
use App\Models\VenueService;
use Illuminate\Support\Str;

class VenueBookingService
{
    /**
     * Create a new venue booking request.
     */
    public function createRequest(array $data, array $serviceIds = [])
    {
        $reference = $this->generateReference();
        $token = Str::random(32);

        $booking = VenueBooking::create(array_merge($data, [
            'reference' => $reference,
            'secure_token' => $token,
            'status' => 'requested',
        ]));

        if (!empty($serviceIds)) {
            $servicesToAttach = [];
            $services = VenueService::whereIn('id', $serviceIds)->get();
            
            foreach ($services as $service) {
                $servicesToAttach[$service->id] = [
                    'snapshot_name' => $service->name,
                    'snapshot_price_type' => $service->price_type,
                    'snapshot_base_price' => $service->base_price,
                    // If it's a fixed price, we can automatically quote it. Otherwise it's null until quoted.
                    'quoted_price' => $service->price_type === 'fixed' ? $service->base_price : null,
                    'is_included' => $service->price_type === 'included',
                ];
            }
            
            $booking->services()->attach($servicesToAttach);
        }

        // TODO: Send notification to admin
        return $booking;
    }

    private function generateReference()
    {
        do {
            // e.g. VAN-V-ABC12
            $reference = 'VAN-V-' . strtoupper(Str::random(5));
        } while (VenueBooking::where('reference', $reference)->exists());

        return $reference;
    }
}
