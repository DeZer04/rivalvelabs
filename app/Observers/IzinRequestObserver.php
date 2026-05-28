<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\IzinRequest;
use App\Models\Absensi;

class IzinRequestObserver
{
    /**
     * Handle the Absensi "created" event.
     */
    public function created(Absensi $absensi): void
    {
    }

    /**
     * Handle the Absensi "updated" event.
     */
    public function updated(Absensi $absensi): void
    {
        if ($izin->status !== 'APPROVED') return;

        app(\App\Services\IzinProcessor::class)
            ->applyToAbsensi($izin);
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