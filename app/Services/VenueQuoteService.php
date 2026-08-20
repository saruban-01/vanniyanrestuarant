<?php

namespace App\Services;

use App\Models\VenueBooking;
use App\Models\VenueQuoteVersion;

class VenueQuoteService
{
    /**
     * Generate a new quote version for a booking.
     */
    public function generateQuote(VenueBooking $booking, $venueFee, $servicePrices = [], $taxAmount = 0, $adminUserId = null)
    {
        $servicesFee = collect($servicePrices)->sum('quoted_price');
        $quotedTotal = $venueFee + $servicesFee + $taxAmount;

        $versionNumber = $booking->quotes()->max('version_number') + 1;

        $quote = VenueQuoteVersion::create([
            'venue_booking_id' => $booking->id,
            'version_number' => $versionNumber,
            'venue_fee' => $venueFee,
            'services_fee' => $servicesFee,
            'tax_amount' => $taxAmount,
            'quoted_total' => $quotedTotal,
            'currency' => 'LKR',
            'admin_user_id' => $adminUserId ?? auth('admin')->id(),
        ]);

        // Update the pivot table quoted prices
        foreach ($servicePrices as $serviceId => $data) {
            $booking->services()->updateExistingPivot($serviceId, [
                'quoted_price' => $data['quoted_price'],
                'is_included' => $data['is_included'] ?? false,
            ]);
        }

        // Update booking status
        if (in_array($booking->status, ['requested', 'under_review', 'quote_pending'])) {
            $booking->update(['status' => 'quote_sent']);
        }

        return $quote;
    }
}
