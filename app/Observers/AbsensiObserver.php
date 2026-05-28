<?php

namespace App\Observers;

use App\Models\Absensi;
use Carbon\Carbon;


class AbsensiObserver
{
    /**
     * Handle the Absensi "created" event.
     */
    public function created(Absensi $absensi): void
    {
        app(\App\Services\AbsensiProcessor::class)
            ->process($absensi);
    }

    /**
     * Handle the Absensi "updated" event.
     */
    public function updated(Absensi $absensi): void
    {
        //
    }

    /**
     * Handle the Absensi "deleted" event.
     */
    public function deleted(Absensi $absensi): void
    {
        //
    }

    /**
     * Handle the Absensi "restored" event.
     */
    public function restored(Absensi $absensi): void
    {
        //
    }

    /**
     * Handle the Absensi "force deleted" event.
     */
    public function forceDeleted(Absensi $absensi): void
    {
        //
    }
}
