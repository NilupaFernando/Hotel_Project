<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CancelExpiredReservations extends Command
{
    protected $signature = 'reservations:cancel-expired';
    protected $description = 'Automatically cancels expired reservations.';

    public function handle()
    {
        $expiredReservations = Reservation::expired()->get();

        foreach ($expiredReservations as $reservation) {
            $reservation->cancel();
        }

        Log::info('Expired reservations have been canceled.');
        $this->info('Expired reservations have been canceled.');
    }
}
