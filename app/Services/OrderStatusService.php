<?php

namespace App\Services;

use App\Models\TakeawayOrder;
use Illuminate\Support\Str;

class OrderStatusService
{
    public const STATUS_RECEIVED = 'received';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Get all valid statuses in order of progression.
     */
    public function getTimelineStatuses(): array
    {
        return [
            self::STATUS_RECEIVED,
            self::STATUS_CONFIRMED,
            self::STATUS_COMPLETED,
        ];
    }

    /**
     * Get customer-friendly heading for the hero section based on status.
     */
    public function getStatusHeading(string $status): string
    {
        return match ($status) {
            self::STATUS_RECEIVED => 'We\'ve Received Your Order',
            self::STATUS_CONFIRMED => 'Your Order Is Confirmed',
            self::STATUS_COMPLETED => 'Order Completed',
            self::STATUS_CANCELLED => 'Order Cancelled',
            default => 'Order Status',
        };
    }

    /**
     * Get customer-friendly description based on status.
     */
    public function getStatusDescription(string $status): string
    {
        return match ($status) {
            self::STATUS_RECEIVED => 'We\'ve received your takeaway order.',
            self::STATUS_CONFIRMED => 'Vanniyan has confirmed your order.',
            self::STATUS_COMPLETED => 'Your order has been completed. Thank you for choosing Vanniyan.',
            self::STATUS_CANCELLED => 'This order has been cancelled. Please contact Vanniyan if you need assistance.',
            default => '',
        };
    }

    /**
     * Generate a new cryptographically secure access token.
     */
    public function generateAccessToken(): string
    {
        return Str::random(40);
    }

    /**
     * Validate if an order exists and the token matches.
     */
    public function validateAccess(string $reference, string $token): ?TakeawayOrder
    {
        return TakeawayOrder::where('reference', $reference)
            ->where('access_token', $token)
            ->first();
    }

    /**
     * Ensure a status transition is valid according to business rules.
     */
    public function isValidTransition(string $currentStatus, string $newStatus): bool
    {
        if ($currentStatus === self::STATUS_CANCELLED || $currentStatus === self::STATUS_COMPLETED) {
            return false; // Terminal states
        }

        if ($newStatus === self::STATUS_CANCELLED) {
            return true; // Can cancel from any non-terminal state
        }

        $timeline = $this->getTimelineStatuses();
        
        $currentIndex = array_search($currentStatus, $timeline);
        $newIndex = array_search($newStatus, $timeline);

        if ($currentIndex === false || $newIndex === false) {
            return false;
        }

        // Only allow moving forward
        return $newIndex > $currentIndex;
    }
}
