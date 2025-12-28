<?php

namespace App\Jobs;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CancelExpiredReservations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Find all pending reservations that have expired
        $expiredReservations = Reservation::where('status', 'pending')
            ->where('expire_at', '<=', now())
            ->get();

        if ($expiredReservations->count() > 0) {
            foreach ($expiredReservations as $reservation) {
                $reservation->update([
                    'status' => 'cancelled',
                    'rejection_reason' => 'Expired - Hotel owner did not respond in time'
                ]);
            }

            Log::info('Expired reservations have been automatically cancelled.', [
                'count' => $expiredReservations->count()
            ]);
        } else {
            Log::info('No expired reservations to cancel.');
        }
    }
}
