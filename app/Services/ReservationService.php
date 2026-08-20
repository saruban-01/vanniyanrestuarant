<?php

namespace App\Services;

use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class ReservationService
{
    protected TableAllocationService $tableService;

    public function __construct(TableAllocationService $tableService)
    {
        $this->tableService = $tableService;
    }

    /**
     * Create a reservation safely with concurrency protection.
     */
    public function createReservation(array $data, string $idempotencyKey): Reservation
    {
        // 1. Check idempotency outside transaction to fail fast
        $existing = Reservation::where('idempotency_key', $idempotencyKey)->first();
        if ($existing) {
            return $existing;
        }

        return DB::transaction(function () use ($data, $idempotencyKey) {
            // Re-check idempotency inside transaction
            $existing = Reservation::where('idempotency_key', $idempotencyKey)->lockForUpdate()->first();
            if ($existing) {
                return $existing;
            }

            // Lock the tables table so no one else can allocate a table concurrently.
            // A more granular approach could lock the specific row of the found table, 
            // but locking the whole table assignment logic prevents race conditions on search.
            // Using DB::raw to lock the reading process if supported, or just locking rows.
            
            // To safely lock, let's find the best table, then attempt to lock that specific row.
            // If it's no longer available after lock, we retry finding another table.
            
            $durationMinutes = 90; // Configured default
            
            // We use pessimistic read/write on the reservations table to prevent overlaps.
            // In a highly concurrent env, we might lock the restaurant_tables row.
            $availableTable = $this->tableService->findAvailableTable(
                $data['reservation_date'],
                $data['reservation_time'],
                $data['guests'],
                $durationMinutes
            );

            if (!$availableTable) {
                throw new Exception("We don't have a suitable table for this group at that time.");
            }

            // Lock the specific table to double check
            $lockedTable = \App\Models\RestaurantTable::where('id', $availableTable->id)->lockForUpdate()->first();
            
            // Re-verify availability after lock
            $stillAvailable = $this->tableService->findAvailableTable(
                $data['reservation_date'],
                $data['reservation_time'],
                $data['guests'],
                $durationMinutes
            );
            
            if (!$stillAvailable || $stillAvailable->id !== $lockedTable->id) {
                // The table was taken between the first check and the lock. 
                // A robust system would loop here, but throwing an exception handles it safely.
                throw new Exception("That time is no longer available. Please try again.");
            }

            // Generate Reference
            do {
                $reference = 'VAN-R-' . strtoupper(Str::random(5));
            } while (Reservation::where('reservation_reference', $reference)->exists());

            // Create Reservation
            $reservation = Reservation::create([
                'reservation_reference' => $reference,
                'customer_name' => $data['customer_name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'reservation_date' => $data['reservation_date'],
                'reservation_time' => $data['reservation_time'],
                'duration_minutes' => $durationMinutes,
                'guests' => $data['guests'],
                'table_id' => $lockedTable->id,
                'special_request' => $data['special_request'] ?? null,
                'status' => 'confirmed', // Assuming auto-confirm for now
                'idempotency_key' => $idempotencyKey,
            ]);

            // Admin Notification
            \App\Models\AdminNotification::notify(
                'NEW_RESERVATION',
                'New Table Reservation',
                "{$reservation->customer_name} reserved for {$reservation->guests} guest(s) on {$reservation->reservation_date} at {$reservation->reservation_time} — Ref: {$reservation->reservation_reference}",
                'reservation',
                $reservation->id
            );

            return $reservation;
        });
    }
}
