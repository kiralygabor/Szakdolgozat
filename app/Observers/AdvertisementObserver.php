<?php

namespace App\Observers;

use App\Models\Advertisement;

class AdvertisementObserver
{
    /**
     * Handle the Advertisement "created" event.
     */
    public function created(Advertisement $advertisement): void
    {
        //
    }

    /**
     * Handle the Advertisement "updated" event.
     */
    public function updated(Advertisement $advertisement): void
    {
        //
    }

    public function deleted(Advertisement $advertisement): void
    {
        if ($advertisement->photos) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($advertisement->photos);
        }
    }

    /**
     * Handle the Advertisement "restored" event.
     */
    public function restored(Advertisement $advertisement): void
    {
        //
    }

    /**
     * Handle the Advertisement "force deleted" event.
     */
    public function forceDeleted(Advertisement $advertisement): void
    {
        //
    }
}
